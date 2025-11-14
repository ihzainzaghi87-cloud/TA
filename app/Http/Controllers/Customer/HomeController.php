<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('customer.home');
    }
}
