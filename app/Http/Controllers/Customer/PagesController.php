<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
     * Display all products listing page.
     */
    public function products(Request $request)
    {
        return view('customer.products.index');
    }

    /**
     * Display the about us page.
     */
    public function about()
    {
        return view('customer.pages.about');
    }

    /**
     * Display the rewards page.
     */
    public function rewards()
    {
        return view('customer.pages.rewards');
    }

    /**
     * Display the blog page.
     */
    public function blog()
    {
        return view('customer.pages.blog');
    }

    /**
     * Display the specified product for customer view.
     */
    public function customerShow($slug)
    {
        $product = Product::with(['category', 'images', 'variations'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_reward', false)
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

    /**
     * Display a listing of published articles.
     */
    public function articles()
    {
        $articles = Article::published()
            ->with('trixRichText')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('customer.blog.index', compact('articles'));
    }

    /**
     * Display the specified article.
     */
    public function articleShow($slug)
    {
        $article = Article::published()
            ->with('trixRichText')
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related articles (excluding current one)
        $relatedArticles = Article::published()
            ->with('trixRichText')
            ->where('id', '!=', $article->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get previous and next articles for navigation
        $previousArticle = Article::published()
            ->where('id', '<', $article->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextArticle = Article::published()
            ->where('id', '>', $article->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('customer.blog.show', compact(
            'article',
            'relatedArticles',
            'previousArticle',
            'nextArticle'
        ));
    }
}
