<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserPoint;
use App\Models\PointTransaction;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        // Set Midtrans Configuration
        Config::$serverKey = config('midtrans.midtrans.server_key');
        Config::$isProduction = config('midtrans.midtrans.is_production');
        Config::$isSanitized = config('midtrans.midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.midtrans.is_3ds');
    }

    /**
     * Store selected products to session for checkout
     */
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
     * Display checkout page with shipping calculation
     */
    public function checkout()
    {
        try {
            // Get cart items
            $selectedVariations = session('selected_variations', []);
            
            if (empty($selectedVariations)) {
                return redirect()->route('cart.index')
                               ->with('error', 'Pilih minimal satu produk terlebih dahulu.');
            }

            $cartItems = Cart::where('user_id', Auth::id())
                            ->whereIn('variation_id', $selectedVariations)
                            ->with(['variation.product.images'])
                            ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                               ->with('error', 'Produk yang dipilih tidak ditemukan.');
            }

            // Calculate totals & weight
            $subtotal = 0;
            $totalPointsNeeded = 0;
            $totalWeight = 0; // ✅ NEW: Calculate weight

            foreach ($cartItems as $item) {
                if ($item->variation && $item->variation->product) {
                    $product = $item->variation->product;
                    $subtotal += $item->quantity * $product->price;
                    
                    if ($product->point_price > 0) {
                        $totalPointsNeeded += $item->quantity * $product->point_price;
                    }

                    // ✅ NEW: Sum total weight
                    $totalWeight += ($product->weight * $item->quantity);
                }
            }

            // ✅ NEW: Minimum weight 1000 gram (1 kg)
            $totalWeight = max($totalWeight, 1000);

            // Calculate points earned (Rp 10.000 = 1 poin)
            $pointsWillEarn = floor($subtotal / 10000);

            // Get user points
            $userPoint = UserPoint::where('user_id', Auth::id())->first();
            $availablePoints = $userPoint ? $userPoint->total_points : 0;

            // Check if user has enough points
            $hasEnoughPoints = $availablePoints >= $totalPointsNeeded;

            // ✅ NEW: Get user addresses
            $addresses = Auth::user()->activeAddresses()
                              ->orderByDesc('is_primary')
                              ->orderBy('created_at', 'desc')
                              ->get();

            // ✅ NEW: Get primary address
            $primaryAddress = Auth::user()->primaryAddress()->first();

            // ✅ NEW: Get origin city for shipping info
            $originCityId = config('rajaongkir.origin_city');

            return view('customer.checkout.index', compact(
                'cartItems',
                'subtotal',
                'totalWeight',          // ✅ NEW
                'availablePoints',
                'pointsWillEarn',
                'totalPointsNeeded',
                'hasEnoughPoints',
                'addresses',            // ✅ NEW
                'primaryAddress',       // ✅ NEW
                'originCityId'          // ✅ NEW
            ));

        } catch (\Exception $e) {
            Log::error('Error loading checkout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman checkout');
        }
    }

    /**
     * Process order and create Midtrans payment
     * ✅ UPDATED: Save shipping data
     */
    public function store(Request $request)
    {
        try {
            // ✅ UPDATED: Validate dengan shipping data
            $validated = $request->validate([
                'user_address_id' => 'required|exists:user_addresses,id',  // ✅ NEW
                'courier' => 'required|string',                              // ✅ NEW
                'service' => 'required|string',                              // ✅ NEW
                'shipping_cost' => 'required|numeric|min:0',                 // ✅ NEW
                'weight' => 'required|integer|min:1',                        // ✅ NEW
                'notes' => 'nullable|string|max:1000',
                'selected_variations' => 'required|string',
            ], [
                'user_address_id.required' => 'Pilih alamat pengiriman',
                'user_address_id.exists' => 'Alamat pengiriman tidak valid',
                'courier.required' => 'Pilih kurir pengiriman',
                'service.required' => 'Pilih layanan pengiriman',
                'shipping_cost.required' => 'Biaya pengiriman tidak ditemukan',
                'weight.required' => 'Berat paket tidak ditemukan',
                'selected_variations.required' => 'Pilih minimal satu produk',
            ]);

            DB::beginTransaction();

            $selectedVariations = session('selected_variations', []);
            if (empty($selectedVariations)) {
                return redirect()->route('cart.index')
                               ->with('error', 'Pilih minimal satu produk terlebih dahulu.');
            }

            $cartItems = Cart::where('user_id', Auth::id())
                            ->whereIn('variation_id', $selectedVariations)
                            ->with('variation.product')
                            ->get();

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return redirect()->route('cart.index')
                               ->with('error', 'Produk yang dipilih tidak ditemukan di keranjang.');
            }

            // ✅ NEW: Get user address data
            $userAddress = UserAddress::where('id', $validated['user_address_id'])
                                      ->where('user_id', Auth::id())
                                      ->firstOrFail();

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

            // ✅ UPDATED: Use shipping cost from form (RajaOngkir)
            $shippingCost = $validated['shipping_cost'];
            $total = $subtotal + $shippingCost;

            // Deduct points from user
            if ($totalPointsNeeded > 0) {
                $userPoint->total_points -= $totalPointsNeeded;
                $userPoint->save();
            }

            // Calculate points earned
            $pointsEarned = floor($subtotal / 10000);

            // ✅ UPDATED: Create order dengan data shipping lengkap
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'user_address_id' => $userAddress->id,                      // ✅ NEW
                
                // Financial data
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'total_points_used' => $totalPointsNeeded,
                'points_earned' => $pointsEarned,
                
                // Status
                'status' => 'Pending',
                'payment_status' => 'Pending',
                
                // ✅ NEW: Shipping snapshot (untuk history)
                'shipping_recipient_name' => $userAddress->recipient_name,
                'shipping_phone' => $userAddress->phone,
                
                // ✅ NEW: RajaOngkir data
                'courier' => strtoupper($validated['courier']),             // ✅ NEW
                'service' => $validated['service'],                          // ✅ NEW
                'weight' => $validated['weight'],                            // ✅ NEW
                'origin_city_id' => config('rajaongkir.origin_city'),       // ✅ NEW
                'destination_city_id' => $userAddress->city_id,              // ✅ NEW
                
                // Notes
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

            // Prepare Midtrans transaction
            $user = Auth::user();

            // Midtrans Configuration
            Config::$serverKey = config('midtrans.midtrans.server_key');
            Config::$isProduction = config('midtrans.midtrans.is_production');
            Config::$isSanitized = config('midtrans.midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.midtrans.is_3ds');
            
            $transactionDetails = [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $total,
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $userAddress->phone,                              // ✅ UPDATED: Use address phone
                'shipping_address' => [                                      // ✅ NEW
                    'first_name' => $userAddress->recipient_name,
                    'phone' => $userAddress->phone,
                    'address' => $userAddress->address,
                    'city' => $userAddress->city_name,
                    'postal_code' => $userAddress->postal_code,
                ],
            ];

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

            // ✅ UPDATED: Tambah shipping detail
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $shippingCost,
                'quantity' => 1,
                'name' => "Ongkir {$validated['courier']} - {$validated['service']}",
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
            ];

            // Get Snap Payment Page URL
            $snapToken = Snap::getSnapToken($params);
            
            // Save snap_token to order
            $order->update(['snap_token' => $snapToken]);

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();
            
            // Clear session
            session()->forget('selected_variations');

            DB::commit();

            Log::info("Order created: {$order->order_number}. Courier: {$validated['courier']}, Service: {$validated['service']}, Shipping: {$shippingCost}");

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
            $notification = new Notification();

            $status = $notification->transaction_status;
            $type = $notification->payment_type;
            $fraud = $notification->fraud_status;
            $orderId = $notification->order_id;

            $order = Order::where('order_number', $orderId)->firstOrFail();

            // Handle notification status
            if ($status == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->payment_status = 'Pending';
                    } else {
                        $order->payment_status = 'Paid';
                        $order->status = 'Processing';

                        // Give earned points when paid
                        $this->giveEarnedPoints($order);
                    }
                }
            } elseif ($status == 'settlement') {
                $order->payment_status = 'Paid';
                $order->status = 'Processing';

                // Give earned points when paid
                $this->giveEarnedPoints($order);
            } elseif ($status == 'pending') {
                $order->payment_status = 'Pending';
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $order->payment_status = 'Failed';
                $order->status = 'Cancelled';
                
                // Rollback points if payment failed
                $this->rollbackPoints($order);
                
                // Restore stock if payment failed
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
     * Give earned points when payment is successful
     */
    private function giveEarnedPoints(Order $order)
    {
        // Cek apakah poin sudah pernah diberikan sebelumnya
        $alreadyGiven = PointTransaction::where('user_id', $order->user_id)
            ->where('transactionable_type', Order::class)
            ->where('transactionable_id', $order->id)
            ->where('type', 'earned')
            ->exists();

        // Jika sudah diberikan, jangan berikan lagi (prevent double earning)
        if ($alreadyGiven) {
            Log::info("Points already given for order #{$order->order_number}");
            return;
        }

        // Berikan poin jika ada
        if ($order->points_earned > 0) {
            $userPoint = UserPoint::firstOrCreate(
                ['user_id' => $order->user_id],
                ['total_points' => 0]
            );

            $userPoint->increment('total_points', $order->points_earned);

            PointTransaction::create([
                'user_id' => $order->user_id,
                'transactionable_type' => Order::class,
                'transactionable_id' => $order->id,
                'type' => 'earned',
                'points' => $order->points_earned,
                'balance_after' => $userPoint->fresh()->total_points,
                'description' => "Mendapatkan {$order->points_earned} poin dari order #{$order->order_number}",
            ]);

            Log::info("Earned points given: {$order->points_earned} for order #{$order->order_number}");
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
        
        do {
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = "ORD-{$date}-{$random}";
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Display order success page
     */
    public function success(Request $request, $orderNumber = null)
    {
        try {
            // ✅ Handle parameter dari route atau query string
            $orderNumber = $orderNumber ?? $request->input('order_id');
            
            // ✅ Log untuk debugging
            Log::info('Success page accessed', [
                'orderNumber' => $orderNumber,
                'user_id' => Auth::id(),
                'query_params' => $request->all(),
            ]);
            
            if (!$orderNumber) {
                Log::warning('Order number is empty');
                return redirect()->route('home')
                            ->with('error', 'Nomor pesanan tidak ditemukan');
            }

            // ✅ Query dengan order_number, BUKAN id
            $order = Order::with(['orderItems.variation.product.images', 'shippingAddress'])
                        ->where('user_id', Auth::id())
                        ->where('order_number', $orderNumber)
                        ->firstOrFail();

            return view('customer.checkout.success', compact('order'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Order not found: {$orderNumber} for user: " . Auth::id());
            
            // ✅ Debug: Cek apakah order ada di database
            $orderExists = Order::where('order_number', $orderNumber)->exists();
            Log::info("Order exists in DB: " . ($orderExists ? 'Yes' : 'No'));
            
            if ($orderExists) {
                Log::warning("Order exists but belongs to different user");
            }
            
            return redirect()->route('home')
                        ->with('error', 'Pesanan tidak ditemukan');
                        
        } catch (\Exception $e) {
            Log::error('Error loading order success: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('home')
                        ->with('error', 'Terjadi kesalahan saat memuat pesanan');
        }
    }

    /**
     * Display user orders
     */
    public function index()
    {
        try {
            $orders = Order::with(['orderItems.variation.product', 'shippingAddress'])
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
            $order = Order::with(['orderItems.variation.product.images', 'shippingAddress'])
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
