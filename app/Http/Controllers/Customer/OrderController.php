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

class OrderController extends Controller
{
    /**
     * Display checkout page
     */
    public function checkout()
    {
        try {
            // Get cart items
            $cartItems = Cart::where('user_id', Auth::id())
                ->with([
                    'variation.product.images',
                    'variation.product.category',
                ])
                ->get();

            // Check if cart is empty
            if ($cartItems->isEmpty()) {
                return redirect()->route('carts.index')
                    ->with('error', 'Keranjang Anda kosong');
            }

            // Calculate totals
            $subtotal = 0;
            $totalPointsNeeded = 0; // Total poin yang dibutuhkan untuk produk dengan point_price

            foreach ($cartItems as $item) {
                if ($item->variation && $item->variation->product) {
                    $product = $item->variation->product;
                    
                    // Hitung subtotal uang
                    $subtotal += $item->quantity * $product->price;
                    
                    // Hitung total poin yang dibutuhkan (jika produk punya point_price)
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

            // Check if user has enough points for products that require points
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
     * Process order
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'shipping_address' => 'required|string|max:500',
                'phone' => 'required|string|max:20',
                'notes' => 'nullable|string|max:1000',
            ], [
                'shipping_address.required' => 'Alamat pengiriman harus diisi',
                'phone.required' => 'Nomor telepon harus diisi',
            ]);

            DB::beginTransaction();

            // Get cart items
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('variation.product')
                ->get();

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return redirect()->route('carts.index')
                    ->with('error', 'Keranjang Anda kosong');
            }

            // Calculate totals and points needed
            $subtotal = 0;
            $totalPointsNeeded = 0; // Poin untuk product yang punya point_price

            foreach ($cartItems as $item) {
                $product = $item->variation->product;
                
                // Check stock
                if ($item->variation->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Stok tidak mencukupi untuk {$product->name}");
                }

                $subtotal += $item->quantity * $product->price;
                
                // Calculate points needed for products with point_price
                if ($product->point_price > 0) {
                    $totalPointsNeeded += $item->quantity * $product->point_price;
                }
            }

            // Get user points
            $userPoint = UserPoint::where('user_id', Auth::id())->first();
            if (!$userPoint) {
                $userPoint = UserPoint::create([
                    'user_id' => Auth::id(),
                    'total_points' => 0
                ]);
            }
            $currentPoints = $userPoint->total_points;

            // Check if user has enough points for products that require points
            if ($totalPointsNeeded > 0 && $currentPoints < $totalPointsNeeded) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', "Poin Anda tidak mencukupi. Dibutuhkan {$totalPointsNeeded} poin, Anda memiliki {$currentPoints} poin");
            }

            // Shipping cost
            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;

            // Deduct points from user (ONLY for products that require points)
            if ($totalPointsNeeded > 0) {
                $userPoint->total_points -= $totalPointsNeeded;
                $userPoint->save();
            }

            // Calculate points earned (Rp 10.000 = 1 poin)
            $pointsEarned = floor($subtotal / 10000);

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'total_points_used' => $totalPointsNeeded, // Only product points
                'points_earned' => $pointsEarned,
                'status' => 'Pending',
                'shipping_address' => $validated['shipping_address'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $variation = $item->variation;
                $product = $variation->product;

                // Create variant details string
                $variantDetails = '';
                if ($variation->color) {
                    $variantDetails .= 'Warna: ' . $variation->color;
                }
                if ($variation->size) {
                    $variantDetails .= ($variantDetails ? ', ' : '') . 'Ukuran: ' . $variation->size;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'variation_id' => $variation->id,
                    'product_name' => $product->name,
                    'variant_details' => $variantDetails ?: '-',
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                    'point_price' => $product->point_price ?? 0,
                    'subtotal' => $product->price * $item->quantity,
                    'point_subtotal' => ($product->point_price ?? 0) * $item->quantity,
                ]);

                // Reduce stock
                $variation->stock -= $item->quantity;
                $variation->save();
            }

            // Get current balance after deduction
            $balanceAfterDeduction = $userPoint->total_points;

            // Create point transaction for PRODUCT POINTS (if any)
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
                $userPoint->total_points += $pointsEarned;
                $userPoint->save();
                
                PointTransaction::create([
                    'user_id' => Auth::id(),
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'earned',
                    'points' => $pointsEarned,
                    'balance_after' => $userPoint->total_points,
                    'description' => "Mendapatkan {$pointsEarned} poin dari order #{$order->order_number}",
                ]);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            Log::info("Order created successfully: {$order->order_number}. Points used: {$totalPointsNeeded}, Points earned: {$pointsEarned}, Final balance: {$userPoint->total_points}");

            return redirect()->route('orders.success', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Gagal membuat pesanan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $date = date('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        return "ORD-{$date}-{$random}";

        // ambil order terakhir di hari ini
        // $lastOrder = Order::whereDate('created_at', date('Y-m-d'))
        //     ->orderBy('order_number', 'desc')
        //     ->first();

        // Ambil 4 digit terakhir lalu increment
        // if ($lastOrder) {
        //     $lastSequence = intval(substr($lastOrder->order_number, -4));
        //     $newSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        // }
        // kalau belum ada order hari ini → mulai dari 0001
        // else {
        //     $newSequence = '0001';
        // }

        // return "ORD-{$date}-{$newSequence}";
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
