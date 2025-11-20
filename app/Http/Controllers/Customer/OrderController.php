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

            foreach ($cartItems as $item) {
                if ($item->variation && $item->variation->product) {
                    $subtotal += $item->quantity * $item->variation->product->price;
                }
            }

            // Shipping cost
            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;

            // Calculate points earned (Rp 10.000 = 1 poin)
            $pointsWillEarn = floor($subtotal / 10000);

            // Get user points
            $userPoint = UserPoint::where('user_id', Auth::id())->first();
            $availablePoints = $userPoint ? $userPoint->points : 0;

            return view('customer.checkout.index', compact(
                'cartItems',
                'subtotal',
                'shippingCost',
                'total',
                'availablePoints',
                'pointsWillEarn'
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
                'use_points' => 'nullable|boolean',
                'points_to_use' => 'nullable|integer|min:0',
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
                return redirect()->route('carts.index')
                    ->with('error', 'Keranjang Anda kosong');
            }

            // Calculate totals
            $subtotal = 0;

            foreach ($cartItems as $item) {
                // Check stock
                if ($item->variation->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Stok tidak mencukupi untuk {$item->variation->product->name}");
                }

                $subtotal += $item->quantity * $item->variation->product->price;
            }

            // Shipping cost (static)
            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;

            // Handle points usage
            $pointsUsed = 0;
            if ($request->use_points && $request->points_to_use > 0) {
                $userPoint = UserPoint::where('user_id', Auth::id())->first();
                
                if ($userPoint && $userPoint->points >= $request->points_to_use) {
                    $pointsUsed = $request->points_to_use;
                    
                    // Deduct points (1 point = Rp 1.000 discount)
                    $pointDiscount = $pointsUsed * 1000;
                    $total = max(0, $total - $pointDiscount);
                    
                    // Update user points - DEDUCT
                    $userPoint->points -= $pointsUsed;
                    $userPoint->save();
                }
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
                'total_points_used' => $pointsUsed,
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

            // Create point transaction for points USED (if any)
            if ($pointsUsed > 0) {
                PointTransaction::create([
                    'user_id' => Auth::id(),
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'deduct',
                    'points' => $pointsUsed,
                    'description' => "Penggunaan {$pointsUsed} poin untuk order #{$order->order_number}",
                ]);
            }

            // Create point transaction for points EARNED
            if ($pointsEarned > 0) {
                PointTransaction::create([
                    'user_id' => Auth::id(),
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'earn',
                    'points' => $pointsEarned,
                    'description' => "Mendapatkan {$pointsEarned} poin dari order #{$order->order_number}",
                ]);

                // Update user points - ADD EARNED POINTS
                $userPoint = UserPoint::firstOrCreate(
                    ['user_id' => Auth::id()],
                    ['points' => 0]
                );
                $userPoint->points += $pointsEarned;
                $userPoint->save();
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            Log::info('Order created successfully: ' . $order->order_number);

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
