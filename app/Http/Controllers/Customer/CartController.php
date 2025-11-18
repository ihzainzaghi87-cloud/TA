<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display cart items
     */
    public function index()
    {
        try {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with([
                    'variation.product.images',
                    'variation.product.category',
                ])
                ->get();

            // Calculate totals
            $totalPrice = 0;
            $totalPointPrice = 0;

            foreach ($cartItems as $item) {
                if ($item->variation && $item->variation->product) {
                    $totalPrice += $item->quantity * $item->variation->product->price;
                    $totalPointPrice += $item->quantity * ($item->variation->product->point_price ?? 0);
                }
            }

            return view('customer.cart.index', compact('cartItems', 'totalPrice', 'totalPointPrice'));
        } catch (\Exception $e) {
            Log::error('Error loading cart: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to load cart');
        }
    }

    /**
     * Add product to cart
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'variation_id' => 'required|exists:variations,id',
                'quantity' => 'required|integer|min:1',
            ], [
                'variation_id.required' => 'Please select a product variation',
                'variation_id.exists' => 'The selected variation is invalid',
                'quantity.required' => 'Please specify the quantity',
                'quantity.integer' => 'Quantity must be a number',
                'quantity.min' => 'Quantity must be at least 1',
            ]);

            // Get variation with product
            $variation = Variation::with('product')->findOrFail($validated['variation_id']);

            // Check stock availability
            if ($variation->stock < $validated['quantity']) {
                return redirect()->back()->with('error', 'Insufficient stock available. Only '.$variation->stock.' items left.');
            }

            // Check if item already exists in cart
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('variation_id', $validated['variation_id'])
                ->first();

            if ($cartItem) {
                // Update quantity if item exists
                $newQuantity = $cartItem->quantity + $validated['quantity'];

                // Check if new quantity exceeds stock
                if ($newQuantity > $variation->stock) {
                    return redirect()->back()->with('error', 'Cannot add more items. Stock limit reached. Maximum available: '.$variation->stock);
                }

                $cartItem->quantity = $newQuantity;
                $cartItem->save();

                Log::info('Cart updated for user: '.Auth::id().', variation: '.$validated['variation_id']);

                return redirect()->back()->with('success', 'Cart updated successfully!');
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => Auth::id(),
                    'variation_id' => $validated['variation_id'],
                    'quantity' => $validated['quantity'],
                ]);

                Log::info('Item added to cart for user: '.Auth::id().', variation: '.$validated['variation_id']);
                return redirect()->back()->with('success', 'Item added to cart successfully!');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Variation not found: '.$e->getMessage());

            return redirect()->back()->with('error', 'Product variation not found');
        } catch (\Exception $e) {
            Log::error('Error adding to cart: '.$e->getMessage());
            dd($e);

            return redirect()->back()->with('error', 'Failed to add item to cart. Please try again.');
        }
    }

    /**
     * Update cart item quantity
     */
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

            // Check stock availability
            if ($cartItem->variation->stock < $validated['quantity']) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $cartItem->variation->stock);
            }

            $cartItem->quantity = $validated['quantity'];
            $cartItem->save();

            Log::info('Cart item updated: ' . $id . ' for user: ' . Auth::id());

            return redirect()->back()->with('success', 'Keranjang berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', 'Data yang dimasukkan tidak valid');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Item keranjang tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error updating cart: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal memperbarui keranjang');
        }
    }

    /**
     * Remove single cart item
     */
    public function destroy($id)
    {
        try {
            $cartItem = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $cartItem->delete();

            Log::info('Cart item deleted: '.$id.' for user: '.Auth::id());

            return redirect()->back()->with('success', 'Item removed from cart');
        } catch (\Exception $e) {
            Log::error('Error deleting cart item: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to remove item');
        }
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        try {
            Cart::where('user_id', Auth::id())->delete();

            Log::info('Cart cleared for user: '.Auth::id());

            return redirect()->back()->with('success', 'Cart cleared successfully');
        } catch (\Exception $e) {
            Log::error('Error clearing cart: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to clear cart');
        }
    }

    /**
     * Get cart summary for AJAX requests
     */
    public function getSummary()
    {
        try {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('variation.product')
                ->get();

            $itemCount = $cartItems->count();
            $totalPrice = 0;
            $totalPointPrice = 0;

            foreach ($cartItems as $item) {
                if ($item->variation && $item->variation->product) {
                    $totalPrice += $item->quantity * $item->variation->product->price;
                    $totalPointPrice += $item->quantity * ($item->variation->product->point_price ?? 0);
                }
            }

            return response()->json([
                'success' => true,
                'itemCount' => $itemCount,
                'totalPrice' => $totalPrice,
                'totalPointPrice' => $totalPointPrice,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cart summary: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart summary',
            ], 500);
        }
    }
}
