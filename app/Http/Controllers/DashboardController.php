<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard'); // resources/views/dashboard.blade.php
    }
}
