
@extends('layouts.public')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">

                    <div class="mb-3">
                        <i class="bi bi-credit-card text-primary"
                           style="font-size: 42px;"></i>
                    </div>

                    <h5 class="fw-bold mb-2">Selesaikan Pembayaran</h5>

                    <p class="text-muted small mb-4">
                        Pesanan kamu berhasil dibuat.
                        Silakan lanjutkan pembayaran melalui Midtrans.
                    </p>

                    <div class="bg-light rounded-3 p-3 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Kode Pesanan</span>
                            <strong class="small">{{ $booking->booking_code }}</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total Pembayaran</span>
                            <strong class="text-primary">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>

                    <button id="pay-button" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-lock-fill me-1"></i>
                        Bayar Sekarang
                    </button>

                    <a href="{{ route('customer.orders.index') }}"
                       class="btn btn-light w-100 mt-2">
                        Bayar Nanti
                    </a>

                    <p class="text-muted mt-3 mb-0" style="font-size: 11px;">
                        Pembayaran diproses secara aman melalui Midtrans.
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script
    src="{{ config('midtrans.is_production')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const payButton = document.getElementById('pay-button');

    payButton.addEventListener('click', function () {

        if (typeof window.snap === 'undefined') {
            alert('Midtrans Snap gagal dimuat. Periksa koneksi dan Client Key.');
            console.error('Midtrans Snap.js tidak ditemukan.');
            return;
        }

        const snapToken = @json($snap->token);

        if (!snapToken) {
            alert('Snap Token kosong. Silakan coba kembali.');
            console.error('Snap Token:', snapToken);
            return;
        }

        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                window.location.href = "{{ route('customer.payment.success', $booking->id) }}";
            },
            onPending: function(result) {
                window.location.href = "{{ route('customer.orders.index') }}";
            },
            onError: function(result) {
                console.error('Midtrans Error:', result);
                alert('Pembayaran gagal. Silakan coba kembali.');
            },
            onClose: function() {
                console.log('Popup pembayaran ditutup.');
            }
        });
    });
});
</script>
@endpush
