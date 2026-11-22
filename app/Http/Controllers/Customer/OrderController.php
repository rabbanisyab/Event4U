<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
            'details.product.category',
            'payment'
        ])
        ->where('user_id', Auth::id())
        ->whereNotIn('status', ['completed', 'cancelled'])
        ->latest()
        ->paginate(10);

        return view('customer.orders.index', compact('bookings'));
    }

    public function history()
    {
        $bookings = Booking::with([
            'details.product.category',
            'payment'
        ])
        ->where('user_id', Auth::id())
        ->whereIn('status', ['completed', 'cancelled'])
        ->latest()
        ->paginate(10);

        return view('customer.history.index', compact('bookings'));
    }
}