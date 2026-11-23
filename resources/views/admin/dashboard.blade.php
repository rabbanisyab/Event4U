
@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@push('styles')
<style>
    .welcome-box {
        background: linear-gradient(120deg, #1d4ed8, #3b82f6);
        color: white;
        border-radius: 16px;
        padding: 25px 28px;
        position: relative;
        overflow: hidden;
    }

    .welcome-box::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.09);
        right: -35px;
        top: -65px;
    }

    .stat-card {
        display: block;
        background: white;
        padding: 20px;
        border-radius: 14px;
        text-decoration: none;
        color: #1e293b;
        box-shadow: 0 2px 10px rgba(0,0,0,.03);
        border: 1px solid #eef2f7;
        transition: .2s;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,.07);
        color: #1e293b;
    }

    .stat-card .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-number {
        font-size: 26px;
        font-weight: 700;
        margin: 12px 0 3px;
    }

    .stat-link {
        font-size: 11px;
        color: #64748b;
    }

    .chart-card {
        background: white;
        border-radius: 14px;
        padding: 22px;
        border: 1px solid #eef2f7;
        height: 100%;
    }

    .table-card {
        background: white;
        border-radius: 14px;
        padding: 22px;
        border: 1px solid #eef2f7;
    }

    .table thead th {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        background: #f8fafc;
        padding: 13px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 14px 13px;
        font-size: 12px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
    }
</style>
@endpush

@section('content')

{{-- WELCOME --}}
<div class="welcome-box mb-4">
    <div class="position-relative" style="z-index:1">
        <span class="small opacity-75">ADMINISTRATOR</span>
        <h4 class="fw-bold mt-2 mb-1">
            Selamat Datang, {{ auth()->user()->name }}!
        </h4>
        <p class="mb-0 small opacity-75">
            Pantau dan kelola seluruh aktivitas penyewaan Event 4U melalui dashboard ini.
        </p>
    </div>
</div>

{{-- STATISTIK --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.bookings.index') }}" class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Total Pesanan</span>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <div class="stat-number">{{ $totalBookings }}</div>
            <span class="stat-link">Lihat semua pesanan <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>

    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.bookings.index') }}" class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Pending</span>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="stat-number">{{ $totalPending }}</div>
            <span class="stat-link">Lihat pesanan <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>

    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.bookings.index') }}" class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Sedang Berlangsung</span>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
            </div>
            <div class="stat-number">{{ $totalOngoing }}</div>
            <span class="stat-link">Lihat pesanan <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>

    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.bookings.index') }}" class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Pesanan Selesai</span>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="stat-number">{{ $totalCompleted }}</div>
            <span class="stat-link">Lihat pesanan <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>

    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.products.index') }}" class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Total Produk</span>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-number">{{ $totalProducts }}</div>
            <span class="stat-link">Kelola produk <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>

    <div class="col-6 col-xl-3">
        <a href="{{ route('admin.users.index') }}" class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Total Pengguna</span>
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-number">{{ $totalUsers }}</div>
            <span class="stat-link">Kelola pengguna <i class="bi bi-arrow-right"></i></span>
        </a>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Total Vendor</span>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
            <div class="stat-number">{{ $totalVendors }}</div>
            <span class="stat-link">Vendor terdaftar</span>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">Pembayaran Berhasil</span>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="stat-number" style="font-size:19px">
                Rp{{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <span class="stat-link">Total pembayaran terverifikasi</span>
        </div>
    </div>

</div>

{{-- GRAFIK --}}
<div class="row g-3 mb-4">

    <div class="col-lg-8">
        <div class="chart-card">
            <div class="mb-3">
                <h6 class="section-title mb-1">Statistik Pesanan</h6>
                <p class="text-secondary small mb-0">Jumlah pesanan dalam 6 bulan terakhir</p>
            </div>
            <div style="height:280px">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-card">
            <div class="mb-3">
                <h6 class="section-title mb-1">Status Pesanan</h6>
                <p class="text-secondary small mb-0">Distribusi seluruh pesanan</p>
            </div>
            <div style="height:280px">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- PESANAN TERBARU --}}
<div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="section-title mb-1">Pesanan Terbaru</h6>
            <p class="text-secondary small mb-0">Lima transaksi terakhir Event 4U</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}"
           class="btn btn-sm btn-outline-primary">
            Lihat Semua
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal Sewa</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestBookings as $booking)
                    @php
                        $colors = [
                            'pending' => 'warning',
                            'confirmed' => 'primary',
                            'ongoing' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                        ];
                        $statusColor = $colors[$booking->status] ?? 'secondary';
                        $paymentStatus = $booking->payment->status ?? 'pending';
                    @endphp

                    <tr>
                        <td class="fw-semibold">{{ $booking->booking_code }}</td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>
                            {{ $booking->start_date?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="fw-semibold">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $paymentStatus === 'verified' ? 'success' : ($paymentStatus === 'rejected' ? 'danger' : 'warning') }}-subtle text-{{ $paymentStatus === 'verified' ? 'success' : ($paymentStatus === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            Belum ada pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const monthLabels = @json($monthlyLabels);
    const bookingData = @json($monthlyBookings);
    const revenueData = @json($monthlyRevenue);

    new Chart(document.getElementById('bookingChart'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Jumlah Pesanan',
                data: bookingData,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                maxBarThickness: 38
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Ongoing', 'Completed', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $totalPending }},
                    {{ $totalConfirmed }},
                    {{ $totalOngoing }},
                    {{ $totalCompleted }},
                    {{ $totalCancelled }}
                ],
                backgroundColor: [
                    '#f59e0b',
                    '#3b82f6',
                    '#06b6d4',
                    '#22c55e',
                    '#ef4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 14,
                        font: { size: 10 }
                    }
                }
            }
        }
    });
</script>
@endpush