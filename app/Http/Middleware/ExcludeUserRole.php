<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ExcludeUserRole
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->hasRole('user')) {
            abort(403);
        }
        return $next($request);
    }
}