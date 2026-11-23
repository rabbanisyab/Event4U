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

        $selectedStatus = request()->query('status', 'all');

        $allowedStatuses = ['all', 'pending', 'confirmed', 'ongoing'];

        if (!in_array($selectedStatus, $allowedStatuses)) {
            $selectedStatus = 'all';
        }

        $statuses = $selectedStatus === 'all'
            ? ['pending', 'confirmed', 'ongoing']
            : [$selectedStatus];

        $bookings = Booking::with([
            'user',
            'details' => function ($query) use ($vendorId, $statuses) {
                $query->whereIn('vendor_status', $statuses)
                    ->whereHas('product', function ($productQuery) use ($vendorId) {
                        $productQuery->where('vendor_id', $vendorId);
                    })
                    ->with('product.category');
            }
        ])
        ->whereHas('details', function ($query) use ($vendorId, $statuses) {
            $query->whereIn('vendor_status', $statuses)
                ->whereHas('product', function ($productQuery) use ($vendorId) {
                    $productQuery->where('vendor_id', $vendorId);
                });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('vendor.orders.index', compact(
            'bookings',
            'selectedStatus'
        ));
    }

    public function history()
    {
        $vendorId = Auth::id();

        $selectedStatus = request()->query('status', 'all');

        $allowedStatuses = ['all', 'completed', 'rejected'];

        if (!in_array($selectedStatus, $allowedStatuses)) {
            $selectedStatus = 'all';
        }

        $statuses = $selectedStatus === 'all'
            ? ['completed', 'rejected']
            : [$selectedStatus];

        $bookings = Booking::with([
            'user',
            'details' => function ($query) use ($vendorId, $statuses) {
                $query->whereIn('vendor_status', $statuses)
                    ->whereHas('product', function ($productQuery) use ($vendorId) {
                        $productQuery->where('vendor_id', $vendorId);
                    })
                    ->with('product.category');
            }
        ])
        ->whereHas('details', function ($query) use ($vendorId, $statuses) {
            $query->whereIn('vendor_status', $statuses)
                ->whereHas('product', function ($productQuery) use ($vendorId) {
                    $productQuery->where('vendor_id', $vendorId);
                });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('vendor.orders.history', compact(
            'bookings',
            'selectedStatus'
        ));
    }

    // UPDATE STATUS PESANAN
    public function updateStatus(Request $request, BookingDetail $bookingDetail)
    {
        $validated = $request->validate([
            'vendor_status' => 'required|in:confirmed,rejected,ongoing,completed',
        ]);

        DB::transaction(function () use ($bookingDetail, $validated) {

            $detail = BookingDetail::with('product')
                ->lockForUpdate()
                ->findOrFail($bookingDetail->id);

            // Pastikan produk milik vendor yang sedang login
            abort_unless(
                $detail->product &&
                (int) $detail->product->vendor_id === (int) Auth::id(),
                403
            );

            $oldStatus = $detail->vendor_status;
            $newStatus = $validated['vendor_status'];

            // Validasi alur perubahan status
            $allowedTransitions = [
                'pending' => ['confirmed', 'rejected'],
                'confirmed' => ['ongoing'],
                'ongoing' => ['completed'],
            ];

            if (!in_array(
                $newStatus,
                $allowedTransitions[$oldStatus] ?? []
            )) {
                throw ValidationException::withMessages([
                    'vendor_status' => 'Perubahan status pesanan tidak diperbolehkan.',
                ]);
            }

            // Update status detail
            $detail->vendor_status = $newStatus;

            // Kembalikan stok hanya sekali ketika ditolak atau selesai
            if (
                in_array($newStatus, ['completed', 'rejected']) &&
                !$detail->stock_restored_at
            ) {
                $detail->product->increment('stock', $detail->quantity);

                $detail->stock_restored_at = now();
            }

            $detail->save();

            // Sinkronisasi status booking utama
            $booking = Booking::whereKey($detail->booking_id)
                ->lockForUpdate()
                ->firstOrFail();

            $allDetails = BookingDetail::where('booking_id', $booking->id)
                ->lockForUpdate()
                ->get();

            $terminalStatuses = ['completed', 'rejected'];

            // Semua detail sudah selesai diproses
            if ($allDetails->every(
                fn ($item) => in_array($item->vendor_status, $terminalStatuses)
            )) {
                $allRejected = $allDetails->every(
                    fn ($item) => $item->vendor_status === 'rejected'
                );

                $booking->update([
                    'status' => $allRejected ? 'cancelled' : 'completed',
                ]);
            }
            // Ada detail yang sedang berjalan
            elseif ($allDetails->contains(
                fn ($item) => $item->vendor_status === 'ongoing'
            )) {
                $booking->update(['status' => 'ongoing']);
            }
            // Ada detail yang sudah dikonfirmasi
            elseif ($allDetails->contains(
                fn ($item) => $item->vendor_status === 'confirmed'
            )) {
                $booking->update(['status' => 'confirmed']);
            }
            // Sisanya masih menunggu
            else {
                $booking->update(['status' => 'pending']);
            }
        });

        return redirect()->route('vendor.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
