<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;

class PagesController extends Controller
{
    public function index()
    {
        // Ambil categories dengan jumlah produk aktif
        $categories = Category::withCount(['products' => function($query) {
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
}
