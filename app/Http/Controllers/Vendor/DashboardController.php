<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        $totalProducts = Product::where('vendor_id', $vendorId)->count();

        $totalAvailable = Product::where('vendor_id', $vendorId)
            ->where('status', 'available')
            ->count();

        $totalUnavailable = Product::where('vendor_id', $vendorId)
            ->where('status', 'unavailable')
            ->count();

        // Menghitung pesanan yang memiliki produk milik vendor
        $totalBookings = Booking::whereHas('details.product', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->distinct()->count();

        $latestProducts = Product::with('category')
            ->where('vendor_id', $vendorId)
            ->latest()
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact(
            'totalProducts',
            'totalAvailable',
            'totalUnavailable',
            'totalBookings',
            'latestProducts'
        ));
    }
}