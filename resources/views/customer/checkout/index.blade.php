
@extends('layouts.public')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Checkout</h4>
        <p class="text-muted small mb-0">Periksa pesananmu sebelum melanjutkan.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('customer.checkout.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold mb-3">Barang yang Disewa</h6>

                        @foreach ($cart->items as $item)
                            @php
                                $days = $item->start_date->diffInDays($item->end_date) + 1;
                                $subtotal = $item->product->price_per_day * $item->quantity * $days;
                            @endphp

                            <div class="d-flex gap-3 py-3 border-bottom">
                                <img
                                    src="{{ $item->product->image
                                        ? (str_starts_with($item->product->image, 'http')
                                            ? $item->product->image
                                            : asset('storage/' . $item->product->image))
                                        : 'https://placehold.co/100x100?text=Produk' }}"
                                    alt="{{ $item->product->name }}"
                                    style="width:85px;height:85px;object-fit:cover;border-radius:8px;"
                                >

                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $item->product->name }}</div>
                                    <div class="text-muted small">
                                        {{ $item->product->category->name ?? 'Umum' }}
                                    </div>
                                    <div class="text-muted small mt-1">
                                        {{ $item->start_date->format('d M Y') }}
                                        - {{ $item->end_date->format('d M Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $item->quantity }} barang × {{ $days }} hari
                                    </div>
                                    <div class="fw-semibold text-primary mt-1">
                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold mb-3">Metode Pengiriman</h6>

                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="delivery_method"
                                id="pickup"
                                value="pickup"
                                {{ old('delivery_method', 'pickup') == 'pickup' ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="pickup">
                                <strong>Ambil Sendiri</strong>
                                <div class="text-muted small">Barang diambil langsung dari vendor.</div>
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="delivery_method"
                                id="delivery"
                                value="delivery"
                                {{ old('delivery_method') == 'delivery' ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="delivery">
                                <strong>Diantar ke Alamat</strong>
                                <div class="text-muted small">Barang dikirim ke alamat tujuan.</div>
                            </label>
                        </div>

                        <div class="mt-3" id="addressField">
                            <label class="form-label small fw-semibold">Alamat Pengiriman</label>
                            <textarea
                                name="delivery_address"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan alamat lengkap..."
                            >{{ old('delivery_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 p-md-4">
                        <label class="form-label fw-semibold">Catatan Pesanan (Opsional)</label>
                        <textarea
                            name="notes"
                            class="form-control"
                            rows="2"
                            placeholder="Tambahkan catatan jika ada..."
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold mb-3">Ringkasan Pembayaran</h6>

                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Jumlah jenis barang</span>
                            <span>{{ $cart->items->count() }} produk</span>
                        </div>

                        <div class="d-flex justify-content-between small mb-3">
                            <span class="text-muted">Subtotal sewa</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold">Total Pembayaran</span>
                            <span class="fw-bold text-primary fs-5">
                                Rp{{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Buat Pesanan
                        </button>

                        <a href="{{ route('customer.cart.index') }}"
                           class="btn btn-light border w-100 mt-2">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
    }

    .form-control {
        font-size: 13px;
        border-radius: 8px;
    }

    .form-check-label {
        font-size: 13px;
    }

    .btn {
        font-size: 13px;
        border-radius: 8px;
        padding: 10px 14px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pickup = document.getElementById('pickup');
    const delivery = document.getElementById('delivery');
    const addressField = document.getElementById('addressField');
    const addressInput = addressField.querySelector('textarea');

    function toggleAddress() {
        addressField.style.display = delivery.checked ? 'block' : 'none';
        addressInput.required = delivery.checked;
    }

    pickup.addEventListener('change', toggleAddress);
    delivery.addEventListener('change', toggleAddress);

    toggleAddress();
});
</script>
@endpush
