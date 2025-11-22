<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserPoint;
use App\Models\PointTransaction;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        try {
            $orders = Order::with('orderItems.variation.product')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return ResponseFormatter::success($orders, 'Orders retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to load orders');
        }
    }

    public function show($orderId)
    {
        try {
            $order = Order::with('orderItems.variation.product.images')
                ->where('user_id', Auth::id())
                ->findOrFail($orderId);
            return ResponseFormatter::success($order, 'Order detail retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error loading order detail: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Order not found');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'shipping_address' => 'required|string|max:500',
                'phone' => 'required|string|max:20',
                'notes' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();

            $cartItems = Auth::user()->cartItems()->with('variation.product')->get();

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return ResponseFormatter::error(null, 'Cart is empty', 400);
            }

            $subtotal = 0;
            $totalPointsNeeded = 0;
            foreach ($cartItems as $item) {
                if ($item->variation->stock < $item->quantity) {
                    DB::rollBack();
                    return ResponseFormatter::error(null, "Insufficient stock for product {$item->variation->product->name}", 400);
                }
                $subtotal += $item->quantity * $item->variation->product->price;
                $totalPointsNeeded += $item->quantity * ($item->variation->product->point_price ?? 0);
            }

            $shippingCost = 15000;
            $total = $subtotal + $shippingCost;

            $userPoint = UserPoint::firstOrCreate(['user_id' => Auth::id()], ['total_points' => 0]);
            $currentPoints = $userPoint->total_points;

            if ($totalPointsNeeded > $currentPoints) {
                DB::rollBack();
                return ResponseFormatter::error(null, "Not enough points. Needed $totalPointsNeeded, you have $currentPoints", 400);
            }

            if ($totalPointsNeeded > 0) {
                $userPoint->decrement('total_points', $totalPointsNeeded);
            }

            $pointsEarned = floor($subtotal / 10000);

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'total_points_used' => $totalPointsNeeded,
                'points_earned' => $pointsEarned,
                'status' => 'Pending',
                'payment_status' => 'Pending',
                'shipping_address' => $validated['shipping_address'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                $variantDetails = collect([
                    $item->variation->color ? "Color: {$item->variation->color}" : null,
                    $item->variation->size ? "Size: {$item->variation->size}" : null,
                ])->filter()->implode(', ') ?: '-';

                OrderItem::create([
                    'order_id' => $order->id,
                    'variation_id' => $item->variation->id,
                    'product_name' => $item->variation->product->name,
                    'variant_details' => $variantDetails,
                    'quantity' => $item->quantity,
                    'price' => $item->variation->product->price,
                    'point_price' => $item->variation->product->point_price ?? 0,
                    'subtotal' => $item->variation->product->price * $item->quantity,
                    'point_subtotal' => ($item->variation->product->point_price ?? 0) * $item->quantity,
                ]);

                $item->variation->decrement('stock', $item->quantity);
            }

            $userPoint->refresh();

            if ($totalPointsNeeded > 0) {
                PointTransaction::create([
                    'user_id' => Auth::id(),
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'redeemed',
                    'points' => $totalPointsNeeded,
                    'balance_after' => $userPoint->total_points,
                    'description' => "Redeemed points for order #{$order->order_number}",
                ]);
            }

            if ($pointsEarned > 0) {
                $userPoint->increment('total_points', $pointsEarned);
                PointTransaction::create([
                    'user_id' => Auth::id(),
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'earned',
                    'points' => $pointsEarned,
                    'balance_after' => $userPoint->fresh()->total_points,
                    'description' => "Earned points from order #{$order->order_number}",
                ]);
            }

            $user = Auth::user();
            $transactionDetails = [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $total,
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $validated['phone'],
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
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $shippingCost,
                'quantity' => 1,
                'name' => 'Shipping Cost',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            Log::info("Order created: {$order->order_number}. Points used: {$totalPointsNeeded}, Points earned: {$pointsEarned}");

            return ResponseFormatter::success([
                'order' => $order,
                'snap_token' => $snapToken,
            ], 'Order created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return ResponseFormatter::error(null, $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to create order');
        }
    }

    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $status = $notification->transaction_status;
            $type = $notification->payment_type;
            $fraud = $notification->fraud_status;
            $orderId = $notification->order_id;

            $order = Order::where('order_number', $orderId)->firstOrFail();

            if ($status == 'capture') {
                if ($type == 'credit_card') {
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

                $this->rollbackPoints($order);
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

    private function rollbackPoints(Order $order)
    {
        if ($order->total_points_used > 0) {
            $userPoint = UserPoint::where('user_id', $order->user_id)->first();
            if ($userPoint) {
                $userPoint->increment('total_points', $order->total_points_used);
                if ($order->points_earned > 0) {
                    $userPoint->decrement('total_points', $order->points_earned);
                }
                PointTransaction::create([
                    'user_id' => $order->user_id,
                    'transactionable_type' => Order::class,
                    'transactionable_id' => $order->id,
                    'type' => 'refund',
                    'points' => $order->total_points_used,
                    'balance_after' => $userPoint->fresh()->total_points,
                    'description' => "Refund {$order->total_points_used} points due to failed payment for order #{$order->order_number}",
                ]);
            }
        }
    }

    private function restoreStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            $item->variation->increment('stock', $item->quantity);
        }
    }

    private function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $orderNumber)->exists());
        return $orderNumber;
    }
}
