<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Booking;
use App\Models\BookingDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        // Statistik produk
        $totalProducts = Product::where('vendor_id', $vendorId)->count();

        $totalAvailable = Product::where('vendor_id', $vendorId)
            ->where('status', 'available')
            ->count();

        $totalUnavailable = Product::where('vendor_id', $vendorId)
            ->where('status', 'unavailable')
            ->count();

        // Statistik pesanan vendor
        $vendorDetails = BookingDetail::whereHas('product', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        });

        $totalBookings = (clone $vendorDetails)
            ->distinct('booking_id')
            ->count('booking_id');

        $totalPending = (clone $vendorDetails)
            ->where('vendor_status', 'pending')
            ->count();

        $totalConfirmed = (clone $vendorDetails)
            ->where('vendor_status', 'confirmed')
            ->count();

        $totalOngoing = (clone $vendorDetails)
            ->where('vendor_status', 'ongoing')
            ->count();

        $totalCompleted = (clone $vendorDetails)
            ->where('vendor_status', 'completed')
            ->count();

        $totalRejected = (clone $vendorDetails)
            ->where('vendor_status', 'rejected')
            ->count();

        // Produk terbaru
        $latestProducts = Product::with('category')
            ->where('vendor_id', $vendorId)
            ->latest()
            ->take(5)
            ->get();

        // Pesanan terbaru yang memiliki produk vendor
        $latestBookings = Booking::with([
            'user',
            'details' => function ($query) use ($vendorId) {
                $query->whereHas('product', function ($productQuery) use ($vendorId) {
                    $productQuery->where('vendor_id', $vendorId);
                })->with('product');
            }
        ])
        ->whereHas('details.product', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
        ->latest()
        ->take(5)
        ->get();

        // Grafik pesanan 6 bulan terakhir
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->startOfMonth()->subMonths($i);
        });

        $monthlyLabels = $months->map(function ($month) {
            return $month->translatedFormat('M Y');
        });

        $monthlyBookings = $months->map(function ($month) use ($vendorId) {
            return Booking::whereHas('details.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->count();
        });

        return view('vendor.dashboard', compact(
            'totalProducts',
            'totalAvailable',
            'totalUnavailable',
            'totalBookings',
            'totalPending',
            'totalConfirmed',
            'totalOngoing',
            'totalCompleted',
            'totalRejected',
            'latestProducts',
            'latestBookings',
            'monthlyLabels',
            'monthlyBookings'
        ));
    }
}