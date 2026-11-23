
@extends('layouts.dashboard')

@section('title', 'Riwayat Pesanan - Event 4U')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Riwayat Pesanan</h4>
            <p class="text-muted mb-0">
                Daftar pesanan yang telah selesai atau ditolak.
            </p>
        </div>

        <div class="order-total-badge">
            <i class="bi bi-clock-history me-2"></i>
            {{ $bookings->total() }} Pesanan
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FILTER RIWAYAT --}}
    @php
        $filters = [
            'all' => 'Semua Riwayat',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
        ];
    @endphp

    <div class="card border-0 shadow-sm mb-4 filter-card">
        <div class="card-body p-3">
            <div class="fw-semibold mb-3">
                <i class="bi bi-funnel me-2 text-primary"></i>
                Filter Riwayat Pesanan
            </div>

            <div class="d-flex flex-wrap gap-2">
                @foreach($filters as $key => $label)
                    <a href="{{ route('vendor.orders.history', ['status' => $key]) }}"
                       class="btn btn-sm rounded-pill px-3
                       {{ $selectedStatus === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                        @if($key === 'completed')
                            <i class="bi bi-check-circle me-1"></i>
                        @elseif($key === 'rejected')
                            <i class="bi bi-x-circle me-1"></i>
                        @else
                            <i class="bi bi-list-ul me-1"></i>
                        @endif
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- DAFTAR RIWAYAT --}}
    <div class="mb-3">
        <h5 class="fw-bold mb-1">Daftar Riwayat</h5>
        <p class="text-muted small mb-0">
            Riwayat transaksi produk yang kamu sewakan.
        </p>
    </div>

    @forelse($bookings as $booking)
        <div class="card border-0 shadow-sm mb-3 history-card">
            <div class="card-body p-4">

                {{-- HEADER PESANAN --}}
                <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $booking->booking_code }}</h6>
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $booking->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>

                    <span class="badge bg-light text-dark border align-self-start">
                        <i class="bi bi-clock-history me-1"></i>
                        Riwayat Pesanan
                    </span>
                </div>

                <hr>

                {{-- CUSTOMER --}}
                <div class="customer-info p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="customer-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Customer</small>
                            <div class="fw-semibold">
                                {{ $booking->user->name ?? '-' }}
                            </div>
                            <small class="text-muted">
                                {{ $booking->user->email ?? '-' }}
                            </small>
                        </div>
                    </div>
                </div>

                {{-- TANGGAL PENYEWAAN --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <small class="text-muted d-block mb-1">Tanggal Mulai</small>
                            <div class="fw-semibold">
                                <i class="bi bi-calendar-event text-primary me-1"></i>
                                {{ $booking->start_date?->format('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <small class="text-muted d-block mb-1">Tanggal Selesai</small>
                            <div class="fw-semibold">
                                <i class="bi bi-calendar-check text-primary me-1"></i>
                                {{ $booking->end_date?->format('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- DETAIL PRODUK --}}
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    Produk yang Dipesan
                </h6>

                @foreach($booking->details as $detail)
                    <div class="p-3 rounded-3 mb-2 history-product">
                        <div class="d-flex justify-content-between flex-wrap gap-3">
                            <div>
                                <div class="fw-semibold">
                                    {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                                </div>

                                <small class="text-muted">
                                    {{ $detail->product->category->name ?? '-' }}
                                </small>

                                <div class="small mt-2">
                                    <i class="bi bi-box me-1 text-muted"></i>
                                    Jumlah: {{ $detail->quantity }} unit
                                </div>
                            </div>

                            <div class="text-md-end">
                                <div class="fw-bold">
                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </div>

                                @if($detail->vendor_status === 'completed')
                                    <span class="badge bg-success-subtle text-success mt-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Selesai
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger mt-2">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-clock-history display-4 text-muted"></i>

                <h5 class="fw-bold mt-3">Belum Ada Riwayat Pesanan</h5>

                <p class="text-muted mb-0">
                    @if($selectedStatus === 'completed')
                        Belum ada pesanan yang selesai.
                    @elseif($selectedStatus === 'rejected')
                        Belum ada pesanan yang ditolak.
                    @else
                        Pesanan yang selesai atau ditolak akan muncul di sini.
                    @endif
                </p>
            </div>
        </div>
    @endforelse

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $bookings->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection

@push('styles')
<style>
    .order-total-badge {
        background: #eff6ff;
        color: #2563eb;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .history-card {
        border-radius: 14px;
    }

    .customer-info {
        background: #f8fafc;
    }

    .customer-avatar {
        width: 43px;
        height: 43px;
        border-radius: 50%;
        background: #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 10px;
        padding: 13px;
        height: 100%;
    }

    .history-product {
        background: #f8fafc;
        border: 1px solid #edf0f4;
    }

    .filter-card,
    .history-card {
        border-radius: 12px;
    }
</style>
@endpush
