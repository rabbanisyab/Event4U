<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $allowedStatuses = ['pending', 'confirmed', 'ongoing'];

        $status = $request->query('status');

        $bookings = Booking::with([
            'details.product.category',
            'payment'
        ])
        ->where('user_id', Auth::id())
        ->whereIn('status', $allowedStatuses)
        ->when(
            in_array($status, $allowedStatuses),
            function ($query) use ($status) {
                $query->where('status', $status);
            }
        )
        ->latest()
        ->paginate(8)
        ->withQueryString();

        return view('customer.orders.index', compact('bookings', 'status'));
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