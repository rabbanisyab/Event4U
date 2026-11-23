
@extends('layouts.dashboard')

@section('title', 'Riwayat Pesanan - Event 4U')

@section('content')
<div class="container-fluid py-3">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Riwayat Pesanan</h4>
        <p class="text-muted mb-0">
            Daftar pesanan yang telah selesai atau ditolak.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($bookings as $booking)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $booking->booking_code }}</h6>
                        <small class="text-muted">
                            {{ $booking->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>

                    <span class="badge bg-light text-dark border align-self-start">
                        Riwayat Pesanan
                    </span>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-muted">Customer</small>
                    <div class="fw-semibold">{{ $booking->user->name ?? '-' }}</div>
                    <small class="text-muted">{{ $booking->user->email ?? '-' }}</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Tanggal Mulai</small>
                        <div class="fw-semibold">
                            {{ $booking->start_date?->format('d M Y') ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Tanggal Selesai</small>
                        <div class="fw-semibold">
                            {{ $booking->end_date?->format('d M Y') ?? '-' }}
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">Produk yang Dipesan</h6>

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
                                    Jumlah: {{ $detail->quantity }} unit
                                </div>
                            </div>

                            <div class="text-md-end">
                                <div class="fw-bold">
                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </div>

                                @if($detail->vendor_status === 'completed')
                                    <span class="badge bg-success-subtle text-success mt-2">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger mt-2">
                                        <i class="bi bi-x-circle me-1"></i>Ditolak
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
                    Pesanan yang selesai atau ditolak akan muncul di sini.
                </p>
            </div>
        </div>
    @endforelse

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>

</div>
@endsection

@push('styles')
<style>
    .history-product {
        background: #f8fafc;
        border: 1px solid #edf0f4;
    }

    .card {
        border-radius: 12px;
    }
</style>
@endpush
