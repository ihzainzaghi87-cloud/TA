<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserPoint;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class OrderController extends Controller
{
    /**
     * Constructor - Set Midtrans configuration once
     */
    public function __construct()
    {
        // ✅ FIX 1: Pindahkan Midtrans config ke constructor (DRY principle)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    // Terima produk yang dipilih dari cart, simpan ke session, lalu redirect ke halaman checkout
    public function selectProducts(Request $request)
    {
        $request->validate(['selected_variations' => 'required']);

        $rawSelection = $request->input('selected_variations');

        if (is_string($rawSelection)) {
            $selectedVariations = json_decode($rawSelection, true);
        } elseif (is_array($rawSelection)) {
            $selectedVariations = $rawSelection;
        } else {
            $selectedVariations = [];
        }

        if (empty($selectedVariations) || !is_array($selectedVariations)) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk.');
        }

        $selectedVariations = array_values(array_unique(array_map('intval', $selectedVariations)));

        session(['selected_variations' => $selectedVariations]);
        Log::info('Session selected_variations set:', $selectedVariations);

        return redirect()->route('checkout');
    }

    /**
     * Display checkout page
     */
    public function checkout()
    {
        try {
            // Get cart items
            $selectedVariations = session('selected_variations', []);

            if (empty($selectedVariations)) {
                return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk terlebih dahulu.');
            }

            $cartItems = Cart::where('user_id', Auth::id())
                ->whereIn('variation_id', $selectedVariations)
                ->with(['variation.product.images'])
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak ditemukan.');
            }

            // Calculate totals
            $subtotal = 0;
            $totalPointsNeeded = 0;

            foreach ($cartItems as $item) {
                if ($item->variation && $item->variation->product) {
                    $product = $item->variation->product;
                    
                    $subtotal += $item->quantity * $product->price;
                    
                    if ($product->point_price > 0) {
                        $totalPointsNeeded += $item->quantity * $product->point_price;
                    }
                }
            }

            // Shipping cost
            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;

            // Calculate points earned (Rp 10.000 = 1 poin)
            $pointsWillEarn = floor($subtotal / 10000);

            // Get user points
            $userPoint = UserPoint::where('user_id', Auth::id())->first();
            $availablePoints = $userPoint ? $userPoint->total_points : 0;

            // Check if user has enough points
            $hasEnoughPoints = $availablePoints >= $totalPointsNeeded;

            return view('customer.checkout.index', compact(
                'cartItems',
                'subtotal',
                'shippingCost',
                'total',
                'availablePoints',
                'pointsWillEarn',
                'totalPointsNeeded',
                'hasEnoughPoints'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading checkout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman checkout');
        }
    }

    /**
     * Process order and create Midtrans payment
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'shipping_address' => 'required|string|max:500',
                'phone' => 'required|string|max:20',
                'notes' => 'nullable|string|max:1000',
                'selected_variations' => 'required|string',
            ], [
                'selected_variations.required' => 'Pilih minimal satu produk',
            ]);

            DB::beginTransaction();

            $selectedVariations = session('selected_variations', []);

            if (empty($selectedVariations)) {
                return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk terlebih dahulu.');
            }

            $cartItems = Cart::where('user_id', Auth::id())
                ->whereIn('variation_id', $selectedVariations)
                ->with('variation.product')
                ->get();

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak ditemukan di keranjang.');
            }

            // Calculate totals and points needed
            $subtotal = 0;
            $totalPointsNeeded = 0;

            foreach ($cartItems as $item) {
                $product = $item->variation->product;
                
                // Check stock
                if ($item->variation->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Stok tidak mencukupi untuk {$product->name}");
                }

                $subtotal += $item->quantity * $product->price;
                
                if ($product->point_price > 0) {
                    $totalPointsNeeded += $item->quantity * $product->point_price;
                }
            }

            // Get user points
            $userPoint = UserPoint::firstOrCreate(
                ['user_id' => Auth::id()],
                ['total_points' => 0]
            );
            
            $currentPoints = $userPoint->total_points;

            // Check if user has enough points
            if ($totalPointsNeeded > 0 && $currentPoints < $totalPointsNeeded) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', "Poin Anda tidak mencukupi. Dibutuhkan {$totalPointsNeeded} poin, Anda memiliki {$currentPoints} poin");
            }

            // Shipping cost
            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;

            // Deduct points from user
            if ($totalPointsNeeded > 0) {
                $userPoint->total_points -= $totalPointsNeeded;
                $userPoint->save();
            }

            // Calculate points earned
            $pointsEarned = floor($subtotal / 10000);

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'total_points_used' => $totalPointsNeeded,
                'points_earned' => $pointsEarned,
                'status' => 'Pending',
                'payment_status' => 'Pending', // ✅ FIX 2: Tambah payment_status
                'shipping_address' => $validated['shipping_address'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $variation = $item->variation;
                $product = $variation->product;

                $variantDetails = collect([
                    $variation->color ? "Warna: {$variation->color}" : null,
                    $variation->size ? "Ukuran: {$variation->size}" : null,
                ])->filter()->implode(', ') ?: '-';

                OrderItem::create([
                    'order_id' => $order->id,
                    'variation_id' => $variation->id,
                    'product_name' => $product->name,
                    'variant_details' => $variantDetails,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                    'point_price' => $product->point_price ?? 0,
                    'subtotal' => $product->price * $item->quantity,
                    'point_subtotal' => ($product->point_price ?? 0) * $item->quantity,
                ]);

                // Reduce stock
                $variation->decrement('stock', $item->quantity);
            }

            // Get current balance after deduction
            $balanceAfterDeduction = $userPoint->total_points;

            // Create point transaction for PRODUCT POINTS
            if ($totalPointsNeeded > 0) {
                PointTransaction::create([
                    'user_id' => Auth::id(),
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'redeemed',
                    'points' => $totalPointsNeeded,
                    'balance_after' => $balanceAfterDeduction,
                    'description' => "Pembayaran {$totalPointsNeeded} poin untuk produk di order #{$order->order_number}",
                ]);
            }

            // Add earned points
            if ($pointsEarned > 0) {
                $userPoint->increment('total_points', $pointsEarned);
                
                PointTransaction::create([
                    'user_id' => Auth::id(),
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'earned',
                    'points' => $pointsEarned,
                    'balance_after' => $userPoint->fresh()->total_points,
                    'description' => "Mendapatkan {$pointsEarned} poin dari order #{$order->order_number}",
                ]);
            }

            // ✅ FIX 3: Midtrans configuration sudah ada di constructor, tidak perlu diulang
            
            // Prepare Midtrans transaction
            $user = Auth::user();
            
            $transactionDetails = [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $total,
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $validated['phone'], // ✅ FIX 4: Gunakan phone dari form, bukan user
            ];

            // ✅ FIX 5: Tambah item_details untuk detail di Midtrans
            $itemDetails = [];
            foreach ($cartItems as $item) {
                $product = $item->variation->product;
                $itemDetails[] = [
                    'id' => $product->id,
                    'price' => (int) $product->price,
                    'quantity' => $item->quantity,
                    'name' => $product->name,
                ];
            }
            
            // Tambah shipping sebagai item
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $shippingCost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
            ];

            // Get Snap Payment Page URL
            $snapToken = Snap::getSnapToken($params);
            
            // ✅ FIX 6: Simpan snap_token ke order
            $order->update(['snap_token' => $snapToken]);

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            Log::info("Order created: {$order->order_number}. Points used: {$totalPointsNeeded}, Points earned: {$pointsEarned}");

            // ✅ FIX 7: Redirect ke payment page dengan snap_token
            return view('customer.checkout.payment', compact('order', 'snapToken'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            dd($e);

            return redirect()->back()
                ->with('error', 'Gagal membuat pesanan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Midtrans callback handler
     */
    public function callback(Request $request)
    {
        try {
            // ✅ FIX 8: Midtrans config sudah ada di constructor
            
            $notification = new Notification();

            $status = $notification->transaction_status;
            $type = $notification->payment_type;
            $fraud = $notification->fraud_status;
            $orderId = $notification->order_id;

            // ✅ FIX 9: Cari order, bukan transaction
            $order = Order::where('order_number', $orderId)->firstOrFail();

            // ✅ FIX 10: Handle notification status dengan benar
            if ($status == 'capture') {
                if ($type == 'credit_card') { // ✅ FIX: Typo 'credit_cart' → 'credit_card'
                    if ($fraud == 'challenge') {
                        $order->payment_status = 'Pending';
                    } else {
                        $order->payment_status = 'Paid';
                        $order->status = 'Processing';
                    }
                }
            } elseif ($status == 'settlement') {
                $order->payment_status = 'Paid';
                $order->status = 'Processing';
            } elseif ($status == 'pending') {
                $order->payment_status = 'Pending';
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $order->payment_status = 'Failed';
                $order->status = 'Cancelled';
                
                // ✅ FIX 11: Rollback points jika payment gagal
                $this->rollbackPoints($order);
                
                // ✅ FIX 12: Restore stock jika payment gagal
                $this->restoreStock($order);
            }

            $order->save();

            Log::info("Payment callback for order {$orderId}: {$status}");

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Rollback points if payment failed
     */
    private function rollbackPoints(Order $order)
    {
        if ($order->total_points_used > 0) {
            $userPoint = UserPoint::where('user_id', $order->user_id)->first();
            if ($userPoint) {
                // Kembalikan poin yang digunakan
                $userPoint->increment('total_points', $order->total_points_used);
                
                // Kurangi poin yang didapat
                if ($order->points_earned > 0) {
                    $userPoint->decrement('total_points', $order->points_earned);
                }
                
                // Create rollback transaction
                PointTransaction::create([
                    'user_id' => $order->user_id,
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'refund',
                    'points' => $order->total_points_used,
                    'balance_after' => $userPoint->fresh()->total_points,
                    'description' => "Pengembalian {$order->total_points_used} poin karena pembayaran gagal untuk order #{$order->order_number}",
                ]);
            }
        }
    }

    /**
     * Restore stock if payment failed
     */
    private function restoreStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            $item->variation->increment('stock', $item->quantity);
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $date = date('Ymd');
        
        // ✅ FIX 13: Gunakan loop untuk ensure unique order number
        do {
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = "ORD-{$date}-{$random}";
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Display order success page
     */
    public function success($orderId)
    {
        try {
            $order = Order::with(['orderItems.variation.product.images'])
                ->where('user_id', Auth::id())
                ->findOrFail($orderId);

            return view('customer.checkout.success', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error loading order success: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Pesanan tidak ditemukan');
        }
    }

    /**
     * Display user orders
     */
    public function index()
    {
        try {
            $orders = Order::with(['orderItems.variation.product'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('customer.orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat daftar pesanan');
        }
    }

    /**
     * Display order detail
     */
    public function show($orderId)
    {
        try {
            $order = Order::with(['orderItems.variation.product.images'])
                ->where('user_id', Auth::id())
                ->findOrFail($orderId);

            return view('customer.orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error loading order detail: ' . $e->getMessage());
            return redirect()->route('orders.index')
                ->with('error', 'Pesanan tidak ditemukan');
        }
    }
}
