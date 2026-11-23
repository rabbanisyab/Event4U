
@extends('layouts.public')

@section('title', 'Pesanan Saya - Event 4U')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Pesanan Saya</h4>
        <p class="text-secondary small mb-0">
            Pantau status penyewaan perlengkapan event kamu.
        </p>
    </div>

    @forelse($bookings as $booking)
        @php
            $paymentStatus = $booking->payment->status ?? 'pending';

            $statusLabel = [
                'pending' => 'Menunggu Konfirmasi',
                'confirmed' => 'Dikonfirmasi',
                'ongoing' => 'Sedang Berlangsung',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ][$booking->status] ?? ucfirst($booking->status);

            $statusColor = [
                'pending' => 'warning',
                'confirmed' => 'success',
                'ongoing' => 'info',
                'completed' => 'success',
                'cancelled' => 'danger',
            ][$booking->status] ?? 'secondary';

            $paymentLabel = [
                'pending' => 'Menunggu Pembayaran',
                'verified' => 'Berhasil Dibayar',
                'rejected' => 'Pembayaran Ditolak',
            ][$paymentStatus] ?? ucfirst($paymentStatus);
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3 p-md-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <small class="text-secondary">Kode Pesanan</small>
                        <h6 class="fw-bold mb-0">
                            {{ $booking->booking_code }}
                        </h6>
                    </div>

                    <span class="badge bg-{{ $statusColor }}">
                        {{ $statusLabel }}
                    </span>
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
                        <small class="text-secondary">Total Pembayaran</small>
                        <p class="fw-bold text-primary mb-0">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <hr>

                <h6 class="fw-semibold mb-3">Detail Produk</h6>

                @foreach($booking->details as $detail)
                    <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
                        <div>
                            <p class="fw-semibold mb-1">
                                {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                            </p>
                            <small class="text-secondary">
                                Jumlah: {{ $detail->quantity }} barang
                            </small>
                        </div>

                        <small class="fw-semibold text-nowrap">
                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                        </small>
                    </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-secondary">
                        Pembayaran:
                        <span class="fw-semibold
                            {{ $paymentStatus === 'verified' ? 'text-success' : '' }}
                            {{ $paymentStatus === 'rejected' ? 'text-danger' : '' }}">
                            {{ $paymentLabel }}
                        </span>
                    </small>

                    @if($paymentStatus === 'pending')
                        <a href="{{ route('customer.payment.pay', $booking->id) }}"
                        class="btn btn-primary btn-sm">
                            <i class="bi bi-credit-card me-1"></i>
                            Bayar Sekarang
                        </a>
                    @endif
                </div>

            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-receipt text-secondary" style="font-size: 40px;"></i>
                <h6 class="fw-bold mt-3">Belum Ada Pesanan</h6>
                <p class="text-secondary small">
                    Pesanan yang kamu buat akan muncul di sini.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                    Cari Produk
                </a>
            </div>
        </div>
    @endforelse

    @if($bookings->hasPages())
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @endif

    <div class="text-end mt-3">
        <a href="{{ route('customer.history.index') }}"
           class="btn btn-outline-primary btn-sm">
            Lihat Riwayat Pesanan
        </a>
    </div>

</div>
@endsection
