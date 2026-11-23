
@extends('layouts.public')

@section('title', 'Pesanan Saya - Event 4U')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="mb-3">
        <h5 class="fw-bold mb-1">Pesanan Saya</h5>
        <p class="text-secondary small mb-0">
            Pantau status penyewaan perlengkapan event kamu.
        </p>
    </div>

    {{-- Filter Status --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('customer.orders.index') }}"
           class="btn btn-sm rounded-pill {{ !$status ? 'btn-primary' : 'btn-outline-secondary' }}">
            Semua Pesanan
        </a>

        <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}"
           class="btn btn-sm rounded-pill {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-secondary' }}">
            Menunggu
        </a>

        <a href="{{ route('customer.orders.index', ['status' => 'confirmed']) }}"
           class="btn btn-sm rounded-pill {{ $status === 'confirmed' ? 'btn-primary' : 'btn-outline-secondary' }}">
            Dikonfirmasi
        </a>

        <a href="{{ route('customer.orders.index', ['status' => 'ongoing']) }}"
           class="btn btn-sm rounded-pill {{ $status === 'ongoing' ? 'btn-primary' : 'btn-outline-secondary' }}">
            Berlangsung
        </a>
    </div>

    {{-- Daftar Pesanan --}}
    @forelse($bookings as $booking)

        @php
            $paymentStatus = $booking->payment->status ?? 'pending';

            $statusLabel = [
                'pending' => 'Menunggu Konfirmasi',
                'confirmed' => 'Dikonfirmasi',
                'ongoing' => 'Sedang Berlangsung',
            ][$booking->status] ?? ucfirst($booking->status);

            $statusColor = [
                'pending' => 'warning',
                'confirmed' => 'success',
                'ongoing' => 'info',
            ][$booking->status] ?? 'secondary';

            $paymentLabel = [
                'pending' => 'Menunggu Pembayaran',
                'verified' => 'Berhasil Dibayar',
                'rejected' => 'Pembayaran Ditolak',
            ][$paymentStatus] ?? ucfirst($paymentStatus);
        @endphp

        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-3">

                {{-- Kode dan Status --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <small class="text-secondary">Kode Pesanan</small>
                        <div class="fw-bold small">
                            {{ $booking->booking_code }}
                        </div>
                    </div>

                    <span class="badge bg-{{ $statusColor }} rounded-pill">
                        {{ $statusLabel }}
                    </span>
                </div>

                <hr class="my-3">

                {{-- Tanggal dan Total --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-secondary">Tanggal Sewa</small>
                        <div class="fw-semibold small">
                            {{ $booking->start_date->format('d M Y') }}
                            - {{ $booking->end_date->format('d M Y') }}
                        </div>
                    </div>

                    <div class="col-6 text-end">
                        <small class="text-secondary">Total Pembayaran</small>
                        <div class="fw-bold text-primary">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Produk --}}
                <div class="bg-light rounded-3 p-3 mb-3">
                    <div class="small fw-semibold mb-2">Detail Produk</div>

                    @foreach($booking->details as $detail)
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <div>
                                <div class="small fw-semibold">
                                    {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                                </div>
                                <small class="text-secondary">
                                    {{ $detail->quantity }} barang
                                </small>
                            </div>

                            <small class="fw-semibold text-nowrap">
                                Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                            </small>
                        </div>
                    @endforeach
                </div>

                {{-- Pembayaran --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="small">
                        <span class="text-secondary">Pembayaran:</span>
                        <span class="fw-semibold
                            {{ $paymentStatus === 'verified' ? 'text-success' : '' }}
                            {{ $paymentStatus === 'rejected' ? 'text-danger' : '' }}">
                            {{ $paymentLabel }}
                        </span>
                    </div>

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

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-5">
                <i class="bi bi-receipt text-secondary" style="font-size: 36px;"></i>

                <h6 class="fw-bold mt-3 mb-1">
                    {{ $status ? 'Pesanan Tidak Ditemukan' : 'Belum Ada Pesanan' }}
                </h6>

                <p class="text-secondary small mb-3">
                    {{ $status
                        ? 'Belum ada pesanan dengan status ini.'
                        : 'Pesanan yang kamu buat akan muncul di sini.' }}
                </p>

                @if($status)
                    <a href="{{ route('customer.orders.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Lihat Semua Pesanan
                    </a>
                @else
                    <a href="{{ route('products.index') }}"
                       class="btn btn-primary btn-sm">
                        Cari Produk
                    </a>
                @endif
            </div>
        </div>

    @endforelse

    {{-- Pagination --}}
    @if($bookings->hasPages())
        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    @endif

    {{-- Riwayat --}}
    <div class="text-end mt-3">
        <a href="{{ route('customer.history.index') }}"
           class="btn btn-outline-primary btn-sm">
            <i class="bi bi-clock-history me-1"></i>
            Lihat Riwayat Pesanan
        </a>
    </div>

</div>
@endsection
