
@extends('layouts.public')

@section('title', 'Riwayat Pesanan - Event 4U')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h4 class="fw-bold">Riwayat Pesanan</h4>
        <p class="text-secondary small">
            Lihat daftar penyewaan yang sudah selesai atau dibatalkan.
        </p>
    </div>

    @forelse($bookings as $booking)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <small class="text-secondary">Kode Pesanan</small>
                        <h6 class="fw-bold mb-0">
                            {{ $booking->booking_code ?? 'EVT-' . $booking->id }}
                        </h6>
                    </div>

                    @if($booking->status === 'completed')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-danger">Dibatalkan</span>
                    @endif
                </div>

                <hr>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <small class="text-secondary">Tanggal Sewa</small>
                        <p class="fw-semibold mb-0">
                            {{ $booking->start_date->format('d M Y') }}
                            - {{ $booking->end_date->format('d M Y') }}
                        </p>
                    </div>

                    <div class="col-md-6">
                        <small class="text-secondary">Total Transaksi</small>
                        <p class="fw-bold text-primary mb-0">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <h6 class="fw-semibold mb-3">Produk yang Disewa</h6>

                @foreach($booking->details as $detail)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <span class="fw-medium">
                                {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                            </span>
                            <small class="text-secondary d-block">
                                {{ $detail->quantity }} barang
                            </small>
                        </div>

                        <small class="fw-semibold">
                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                        </small>
                    </div>
                @endforeach

            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-clock-history text-secondary" style="font-size: 40px;"></i>
                <h6 class="fw-bold mt-3">Belum Ada Riwayat</h6>
                <p class="text-secondary small">
                    Pesanan yang selesai atau dibatalkan akan muncul di sini.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                    Jelajahi Produk
                </a>
            </div>
        </div>
    @endforelse

    @if($bookings->hasPages())
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @endif

</div>
@endsection
