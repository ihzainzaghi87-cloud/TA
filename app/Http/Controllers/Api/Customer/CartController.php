<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Cart;
use App\Models\Variation;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        try {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('variation.product.images', 'variation.product.category')
                ->get();

            $totalPrice = 0;
            $totalPointPrice = 0;

            foreach ($cartItems as $item) {
                if ($item->variation) {
                    $totalPrice += $item->quantity * $item->variation->product->price;
                    $totalPointPrice += $item->quantity * ($item->variation->product->point_price ?? 0);
                }
            }

            return ResponseFormatter::success([
                'cartItems' => $cartItems,
                'totalPrice' => $totalPrice,
                'totalPointPrice' => $totalPointPrice,
            ], 'Cart items retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error loading cart: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to load cart');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'variation_id' => 'required|exists:variations,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $variation = Variation::with('product')->findOrFail($validated['variation_id']);

            if ($variation->stock < $validated['quantity']) {
                return ResponseFormatter::error(null, "Insufficient stock. Only $variation->stock available", 422);
            }

            $cartItem = Cart::where('user_id', Auth::id())
                ->where('variation_id', $validated['variation_id'])
                ->first();

            if ($cartItem) {
                $newQty = $cartItem->quantity + $validated['quantity'];
                if ($newQty > $variation->stock) {
                    return ResponseFormatter::error(null, "Cannot add more items. Stock limit reached: $variation->stock", 422);
                }
                $cartItem->quantity = $newQty;
                $cartItem->save();

                Log::info("Cart updated for user " . Auth::id() . ", variation " . $validated['variation_id']);
                return ResponseFormatter::success($cartItem, 'Cart updated successfully');
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'variation_id' => $validated['variation_id'],
                    'quantity' => $validated['quantity'],
                ]);

                Log::info("Item added to cart for user " . Auth::id() . ", variation " . $validated['variation_id']);
                return ResponseFormatter::success($cartItem, 'Item added to cart successfully');
            }
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to add item to cart');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cartItem = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->with('variation')
                ->firstOrFail();

            if ($cartItem->variation->stock < $validated['quantity']) {
                return ResponseFormatter::error($cartItem, "Insufficient stock: {$cartItem->variation->stock}", 422);
            }

            $cartItem->quantity = $validated['quantity'];
            $cartItem->save();

            Log::info("Cart item updated: $id for user " . Auth::id());
            return ResponseFormatter::success($cartItem, 'Cart updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating cart: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to update cart item');
        }
    }

    public function destroy($id)
    {
        try {
            $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $cartItem->delete();

            Log::info("Cart item deleted: $id for user " . Auth::id());
            return ResponseFormatter::success($cartItem, 'Cart item removed successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting cart item: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to remove cart item');
        }
    }

    public function clear()
    {
        try {
            Cart::where('user_id', Auth::id())->delete();
            Log::info("Cart cleared for user " . Auth::id());
            return ResponseFormatter::success(null, 'Cart cleared successfully');
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to clear cart');
        }
    }

    public function getSummary()
    {
        try {
            $cartItems = Cart::where('user_id', Auth::id())->with('variation.product')->get();
            $itemCount = $cartItems->count();
            $totalPrice = 0;
            $totalPointPrice = 0;

            foreach ($cartItems as $item) {
                if ($item->variation) {
                    $totalPrice += $item->quantity * $item->variation->product->price;
                    $totalPointPrice += $item->quantity * ($item->variation->product->point_price ?? 0);
                }
            }

            return ResponseFormatter::success([
                'itemCount' => $itemCount,
                'totalPrice' => $totalPrice,
                'totalPointPrice' => $totalPointPrice,
            ], 'Cart summary retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error getting cart summary: ' . $e->getMessage());
            return ResponseFormatter::error(null, 'Failed to get cart summary');
        }
    }
}
