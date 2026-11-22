<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalBookings = Booking::count();
        $totalPending = Booking::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalBookings',
            'totalPending'
        ));
    }
}