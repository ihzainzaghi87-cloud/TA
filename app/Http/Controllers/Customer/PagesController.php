<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class PagesController extends Controller
{
    public function index()
    {
        // Ambil categories dengan jumlah produk aktif
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name')
            ->get();

        // Ambil produk populer (misal: 8 produk terbaru yang aktif)
        $popularProducts = Product::where('is_active', true)
            ->with(['category', 'images', 'variations'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Ambil banners jika ada
        $banners = Banner::active()->orderBy('created_at', 'desc')->get();

        return view('customer.home', compact('categories', 'popularProducts', 'banners'));
    }

    /**
     * Display the specified product for customer view.
     */
    public function customerShow($slug)
    {
        $product = Product::with(['category', 'images', 'variations'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related products
        $relatedProducts = Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Get stock information
        $totalStock = $product->variations->sum('stock');
        $availableVariations = $product->variations->where('stock', '>', 0);

        // Get unique colors and sizes
        $colors = $product->variations->pluck('color')->unique()->filter();
        $sizes = $product->variations->pluck('size')->unique()->filter();

        return view('customer.products.detail', compact(
            'product',
            'relatedProducts',
            'totalStock',
            'availableVariations',
            'colors',
            'sizes'
        ));
    }
}
