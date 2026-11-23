<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

            // Status selesai atau ditolak tidak boleh diproses ulang.
            if (in_array($oldStatus, ['completed', 'rejected'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'vendor_status' => 'Pesanan ini sudah selesai diproses.',
                ]);
            }

            $detail->vendor_status = $newStatus;

            // Kembalikan stok ketika barang selesai disewa
            // atau pesanan ditolak.
            if (
                in_array($newStatus, ['completed', 'rejected']) &&
                !$detail->stock_restored_at
            ) {
                $product = $detail->product;

                $product->increment('stock', $detail->quantity);

                $detail->stock_restored_at = now();
            }

            $detail->save();
        });

        return redirect()->route('vendor.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
