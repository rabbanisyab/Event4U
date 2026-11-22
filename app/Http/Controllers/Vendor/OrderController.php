<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        $bookings = Booking::with([
            'user',
            'details' => function ($query) use ($vendorId) {
                $query->whereHas('product', function ($productQuery) use ($vendorId) {
                    $productQuery->where('vendor_id', $vendorId);
                })->with('product.category');
            }
        ])
        ->whereHas('details.product', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
        ->latest()
        ->get();

        return view('vendor.orders.index', compact('bookings'));
    }

    public function updateStatus(Request $request, BookingDetail $bookingDetail)
    {
        $request->validate([
            'vendor_status' => 'required|in:confirmed,rejected,ongoing,completed',
        ]);

        $bookingDetail->load('product');

        abort_unless(
            $bookingDetail->product &&
            (int) $bookingDetail->product->vendor_id === (int) Auth::id(),
            403
        );

        $bookingDetail->update([
            'vendor_status' => $request->vendor_status,
        ]);

        return redirect()->route('vendor.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}