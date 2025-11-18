<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Symfony\Component\HttpFoundation\Response;

class ShareCartCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Initialize cart count
        $cartCount = 0;
        
        // Get cart count for authenticated users
        if (Auth::check()) {
            $cartCount = Cart::where('user_id', Auth::id())->count();
        }
        
        // Share with all views
        View::share('cartCount', $cartCount);
        
        return $next($request);
    }
}
