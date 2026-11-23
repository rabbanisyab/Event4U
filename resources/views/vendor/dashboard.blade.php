@extends('layouts.dashboard')

@section('title', 'Dashboard Vendor - Event 4U')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard Vendor</h3>
            <p class="text-muted mb-0">
                Selamat datang, {{ auth()->user()->name }}! Kelola bisnis penyewaan event kamu di sini.
            </p>
        </div>

        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        @php
            $stats = [
                ['label' => 'Total Produk', 'value' => $totalProducts, 'icon' => 'bi-box-seam', 'color' => 'primary'],
                ['label' => 'Produk Tersedia', 'value' => $totalAvailable, 'icon' => 'bi-check-circle', 'color' => 'success'],
                ['label' => 'Produk Tidak Tersedia', 'value' => $totalUnavailable, 'icon' => 'bi-x-circle', 'color' => 'danger'],
                ['label' => 'Total Pesanan', 'value' => $totalBookings, 'icon' => 'bi-receipt', 'color' => 'info'],
                ['label' => 'Menunggu Konfirmasi', 'value' => $totalPending, 'icon' => 'bi-clock', 'color' => 'warning'],
                ['label' => 'Dikonfirmasi', 'value' => $totalConfirmed, 'icon' => 'bi-check2-circle', 'color' => 'primary'],
                ['label' => 'Sedang Berjalan', 'value' => $totalOngoing, 'icon' => 'bi-arrow-repeat', 'color' => 'info'],
                ['label' => 'Selesai', 'value' => $totalCompleted, 'icon' => 'bi-check-circle-fill', 'color' => 'success'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-2">{{ $stat['label'] }}</p>
                                <h3 class="fw-bold mb-0">{{ $stat['value'] }}</h3>
                            </div>
                            <div class="stat-icon text-{{ $stat['color'] }} bg-{{ $stat['color'] }}-subtle">
                                <i class="bi {{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    {{-- GRAFIK --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Statistik Pesanan</h5>
                    <p class="text-muted small mb-3">Jumlah pesanan dalam 6 bulan terakhir</p>
                    <div style="height: 280px;">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Status Pesanan</h5>
                    <p class="text-muted small mb-3">Distribusi status produk dalam pesanan</p>
                    <div style="height: 280px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- PESANAN TERBARU --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Pesanan Terbaru</h5>
                    <p class="text-muted small mb-0">Pesanan yang melibatkan produk kamu</p>
                </div>
                <a href="{{ route('vendor.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Customer</th>
                            <th>Tanggal Sewa</th>
                            <th>Total Pesanan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestBookings as $booking)
                            <tr>
                                <td class="fw-semibold">{{ $booking->booking_code }}</td>
                                <td>{{ $booking->user->name ?? '-' }}</td>
                                <td>
                                    {{ $booking->start_date?->format('d M Y') }}
                                    -
                                    {{ $booking->end_date?->format('d M Y') }}
                                </td>
                                <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statuses = $booking->details->pluck('vendor_status')->unique();
                                    @endphp

                                    @if ($statuses->every(fn($status) => $status === 'completed'))
                                        <span class="badge bg-success-subtle text-success">Selesai</span>
                                    @elseif ($statuses->contains('ongoing'))
                                        <span class="badge bg-info-subtle text-info">Berjalan</span>
                                    @elseif ($statuses->contains('pending'))
                                        <span class="badge bg-warning-subtle text-warning">Menunggu</span>
                                    @elseif ($statuses->contains('confirmed'))
                                        <span class="badge bg-primary-subtle text-primary">Dikonfirmasi</span>
                                    @elseif ($statuses->every(fn($status) => $status === 'rejected'))
                                        <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada pesanan untuk produk kamu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PRODUK TERBARU --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Produk Terbaru</h5>
                    <p class="text-muted small mb-0">Daftar perlengkapan event milik kamu</p>
                </div>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-sm btn-outline-primary">
                    Kelola Produk
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Sewa / Hari</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestProducts as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>Rp{{ number_format($product->price_per_day, 0, ',', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    @if ($product->status === 'available')
                                        <span class="badge bg-success-subtle text-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Tidak Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Kamu belum memiliki produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: transform .2s ease, box-shadow .2s ease;
        border-radius: 12px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, .08) !important;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .card {
        border-radius: 12px;
    }

    .table th {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        white-space: nowrap;
    }

    .table td {
        font-size: 13px;
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const bookingLabels = @json($monthlyLabels);
    const bookingData = @json($monthlyBookings);

    new Chart(document.getElementById('bookingChart'), {
        type: 'bar',
        data: {
            labels: bookingLabels,
            datasets: [{
                label: 'Jumlah Pesanan',
                data: bookingData,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: [
                'Menunggu',
                'Dikonfirmasi',
                'Berjalan',
                'Selesai',
                'Ditolak'
            ],
            datasets: [{
                data: [
                    {{ $totalPending }},
                    {{ $totalConfirmed }},
                    {{ $totalOngoing }},
                    {{ $totalCompleted }},
                    {{ $totalRejected }}
                ],
                backgroundColor: [
                    '#f59e0b',
                    '#3b82f6',
                    '#06b6d4',
                    '#10b981',
                    '#ef4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush