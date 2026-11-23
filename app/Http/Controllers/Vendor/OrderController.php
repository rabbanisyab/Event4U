<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $validated = $request->validate([
            'vendor_status' => 'required|in:confirmed,rejected,ongoing,completed',
        ]);

        DB::transaction(function () use ($bookingDetail, $validated) {

            $detail = BookingDetail::with('product')
                ->lockForUpdate()
                ->findOrFail($bookingDetail->id);

            abort_unless(
                $detail->product &&
                (int) $detail->product->vendor_id === (int) Auth::id(),
                403
            );

            $oldStatus = $detail->vendor_status;
            $newStatus = $validated['vendor_status'];

            if (in_array($oldStatus, ['completed', 'rejected'])) {
                throw ValidationException::withMessages([
                    'vendor_status' => 'Pesanan ini sudah selesai diproses.',
                ]);
            }

            $detail->vendor_status = $newStatus;

            if (
                in_array($newStatus, ['completed', 'rejected']) &&
                !$detail->stock_restored_at
            ) {
                $detail->product->increment('stock', $detail->quantity);
                $detail->stock_restored_at = now();
            }

            $detail->save();

            // Sinkronisasi status pesanan utama.
            $booking = Booking::whereKey($detail->booking_id)
                ->lockForUpdate()
                ->firstOrFail();

            $allDetails = BookingDetail::where('booking_id', $booking->id)
                ->lockForUpdate()
                ->get();

            $terminalStatuses = ['completed', 'rejected'];

            // Jika seluruh detail sudah selesai diproses.
            if ($allDetails->every(
                fn ($item) => in_array($item->vendor_status, $terminalStatuses)
            )) {
                $allRejected = $allDetails->every(
                    fn ($item) => $item->vendor_status === 'rejected'
                );

                $booking->update([
                    'status' => $allRejected ? 'cancelled' : 'completed',
                ]);
            } elseif ($allDetails->contains(
                fn ($item) => $item->vendor_status === 'ongoing'
            )) {
                $booking->update([
                    'status' => 'ongoing',
                ]);
            }
        });

        return redirect()->route('vendor.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
