
@extends('layouts.public')

@section('content')
<div class="container py-4">
    <div class="payment-success-card">

        @if($booking->payment && $booking->payment->status === 'verified')
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <h4>Pembayaran Berhasil!</h4>
            <p class="subtitle">
                Terima kasih! Pembayaran pesanan kamu sudah berhasil dikonfirmasi.
            </p>
        @else
            <div class="success-icon pending-icon">
                <i class="bi bi-clock"></i>
            </div>

            <h4>Pembayaran Diterima!</h4>
            <p class="subtitle">
                Pembayaran kamu sedang dikonfirmasi. Silakan cek kembali status pesanan beberapa saat lagi.
            </p>
        @endif

        <div class="order-info">
            <div class="info-row">
                <span>Kode Pesanan</span>
                <strong>{{ $booking->booking_code }}</strong>
            </div>

            <div class="info-row">
                <span>Total Pembayaran</span>
                <strong class="total">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </strong>
            </div>

            <div class="info-row">
                <span>Status Pembayaran</span>
                <strong>
                    {{ $booking->payment->status === 'verified' ? 'Berhasil' : 'Menunggu Konfirmasi' }}
                </strong>
            </div>
        </div>

        <a href="{{ route('customer.orders.index') }}" class="btn-orders">
            Lihat Pesanan Saya
        </a>

        <a href="{{ route('home') }}" class="back-home">
            Kembali ke Beranda
        </a>
    </div>
</div>

<style>
.payment-success-card {
    max-width: 480px;
    margin: 20px auto;
    padding: 30px;
    background: #fff;
    border-radius: 14px;
    text-align: center;
    box-shadow: 0 3px 15px rgba(0,0,0,.06);
}

.success-icon {
    width: 65px;
    height: 65px;
    margin: 0 auto 17px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #dcfce7;
    color: #16a34a;
    font-size: 32px;
}

.pending-icon {
    background: #fef3c7;
    color: #d97706;
}

.payment-success-card h4 {
    font-size: 21px;
    font-weight: 700;
    color: #172033;
    margin-bottom: 8px;
}

.subtitle {
    font-size: 13px;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 24px;
}

.order-info {
    background: #f8fafc;
    border-radius: 10px;
    padding: 16px;
    text-align: left;
    margin-bottom: 22px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 13px;
    font-size: 12px;
    color: #64748b;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-row strong {
    color: #1e293b;
    text-align: right;
}

.info-row .total {
    color: #2563eb;
    font-size: 14px;
}

.btn-orders {
    display: block;
    background: #2454dc;
    color: white;
    padding: 11px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

.btn-orders:hover {
    background: #1d45bd;
    color: white;
}

.back-home {
    display: inline-block;
    margin-top: 15px;
    color: #64748b;
    font-size: 12px;
    text-decoration: none;
}

@media (max-width: 576px) {
    .payment-success-card {
        padding: 22px 17px;
    }
}
</style>
@endsection
