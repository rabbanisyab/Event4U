<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::where('user_id', Auth::id())->count();

        $cart = Cart::where('user_id', Auth::id())->first();
        $totalCartItems = $cart ? $cart->items()->count() : 0;

        $latestProducts = Product::with('category')
            ->where('status', 'available')
            ->latest()
            ->take(4)
            ->get();

        return view('customer.dashboard', compact(
            'totalBookings',
            'totalCartItems',
            'latestProducts'
        ));
    }
}
