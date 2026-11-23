<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalBookings = Booking::count();

        $totalPending = Booking::where('status', 'pending')->count();
        $totalConfirmed = Booking::where('status', 'confirmed')->count();
        $totalOngoing = Booking::where('status', 'ongoing')->count();
        $totalCompleted = Booking::where('status', 'completed')->count();
        $totalCancelled = Booking::where('status', 'cancelled')->count();

        $totalUsers = User::count();
        $totalVendors = User::where('role', 'vendor')->count();

        // Total pembayaran yang berhasil diverifikasi
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');

        // Pesanan terbaru
        $latestBookings = Booking::with(['user', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        // Grafik 6 bulan terakhir
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->startOfMonth()->subMonths($i);
        });

        $monthlyLabels = $months->map(function ($month) {
            return $month->translatedFormat('M Y');
        });

        $monthlyBookings = $months->map(function ($month) {
            return Booking::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        });

        $monthlyRevenue = $months->map(function ($month) {
            return Payment::where('status', 'verified')
                ->whereYear('updated_at', $month->year)
                ->whereMonth('updated_at', $month->month)
                ->sum('amount');
        });

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalBookings',
            'totalPending',
            'totalConfirmed',
            'totalOngoing',
            'totalCompleted',
            'totalCancelled',
            'totalUsers',
            'totalVendors',
            'totalRevenue',
            'latestBookings',
            'monthlyLabels',
            'monthlyBookings',
            'monthlyRevenue'
        ));
    }
}