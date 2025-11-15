<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class HomeController extends Controller
{
    public function __invoke()
    {
        // Get active banners from database, ordered by creation date
        $banners = Banner::active()->orderBy('created_at', 'desc')->get();
        
        return view('customer.home', compact('banners'));
    }
}
