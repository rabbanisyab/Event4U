
@extends('layouts.public')

@section('content')

<style>

.product-detail {
    max-width: 1000px;
    margin: auto;
}

.back-link {
    color: #64748b;
    font-size: 12px;
    text-decoration: none;
}

.product-image-box {
    background: #f5f7fa;
    border-radius: 12px;
    overflow: hidden;
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image-box img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.product-category {
    display: inline-block;
    background: #eaf2ff;
    color: #2563eb;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.product-title {
    font-size: 22px;
    font-weight: 700;
    color: #172033;
    line-height: 1.4;
}

.product-price {
    font-size: 21px;
    font-weight: 700;
    color: #2563eb;
}

.product-description {
    color: #64748b;
    font-size: 12px;
    line-height: 1.7;
}

.info-box {
    background: #f8fafc;
    border: 1px solid #edf0f5;
    border-radius: 9px;
    padding: 10px;
}

.info-label {
    color: #94a3b8;
    font-size: 10px;
    margin-bottom: 3px;
}

.info-value {
    color: #263449;
    font-size: 12px;
    font-weight: 600;
}

.booking-card {
    border: 1px solid #e9edf3;
    border-radius: 12px;
    padding: 15px;
    background: white;
}

.booking-card .form-label {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 5px;
}

.booking-card .form-control {
    border-radius: 7px;
    padding: 8px 10px;
    font-size: 12px;
}

.btn-rent {
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    padding: 10px;
}

.section-heading {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
}

@media (max-width: 767px) {
    .product-image-box {
        height: 260px;
    }

    .product-title {
        font-size: 20px;
    }

    .product-price {
        font-size: 20px;
    }
}

</style>

<div class="pt-1 pb-3">
    <div class="product-detail">

        @if(session('success'))
            <div class="alert alert-success mt-3 small">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3 small">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mt-3 small">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="row g-4 g-lg-5 mt-2">

            {{-- FOTO PRODUK --}}
            <div class="col-lg-6">
                <div class="product-image-box">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http')
                            ? $product->image
                            : asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}">
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                            <small>Gambar belum tersedia</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- INFORMASI PRODUK --}}
            <div class="col-lg-6">

                <span class="product-category">
                    {{ $product->category->name ?? 'Umum' }}
                </span>

                <h1 class="product-title mt-3 mb-2">
                    {{ $product->name }}
                </h1>

                <div class="product-price mb-3">
                    Rp{{ number_format($product->price_per_day, 0, ',', '.') }}
                    <span class="text-secondary fw-normal fs-6">/ hari</span>
                </div>

                <hr class="my-4">

                <h6 class="section-heading mb-2">Deskripsi Produk</h6>

                <p class="product-description mb-4">
                    {{ $product->description ?? 'Belum ada deskripsi produk.' }}
                </p>

                {{-- INFORMASI STOK --}}
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="info-box">
                            <div class="info-label">Ketersediaan</div>
                            <div class="info-value">
                                @if($product->status === 'available' && $product->stock > 0)
                                    <span class="text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="text-danger">
                                        Tidak tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="info-box">
                            <div class="info-label">Stok saat ini</div>
                            <div class="info-value">
                                {{ $product->stock }} unit
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM PENYEWAAN --}}
                @if($product->status === 'available' && $product->stock > 0)

                    @if(auth()->check() && auth()->user()->role === 'customer')

                        <div class="booking-card">
                            <h6 class="section-heading mb-3">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>
                                Atur Penyewaan
                            </h6>

                            <form action="{{ route('customer.cart.store') }}"
                                  method="POST">
                                @csrf

                                <input type="hidden"
                                       name="product_id"
                                       value="{{ $product->id }}">

                                <div class="mb-3">
                                    <label class="form-label">Jumlah barang</label>
                                    <input type="number"
                                           name="quantity"
                                           class="form-control"
                                           value="{{ old('quantity', 1) }}"
                                           min="1"
                                           max="{{ $product->stock }}"
                                           required>
                                </div>

                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Tanggal mulai</label>
                                        <input type="date"
                                               name="start_date"
                                               class="form-control"
                                               value="{{ old('start_date') }}"
                                               min="{{ now()->toDateString() }}"
                                               required>
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Tanggal selesai</label>
                                        <input type="date"
                                               name="end_date"
                                               class="form-control"
                                               value="{{ old('end_date') }}"
                                               min="{{ now()->toDateString() }}"
                                               required>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="btn btn-rent w-100 mt-4">
                                    <i class="bi bi-cart-plus me-2"></i>
                                    Tambah ke Keranjang
                                </button>

                            </form>
                        </div>

                    @elseif(!auth()->check())

                        <a href="{{ route('login') }}"
                           class="btn btn-rent w-100 mt-2">
                            Login untuk Menyewa
                        </a>

                    @else

                        <div class="alert alert-info small">
                            Akun admin dan vendor tidak dapat melakukan penyewaan.
                        </div>

                    @endif

                @else

                    <button class="btn btn-secondary w-100 py-3" disabled>
                        Produk Tidak Tersedia
                    </button>

                @endif

            </div>
        </div>

        {{-- INFORMASI TAMBAHAN --}}
        <div class="mt-5 pt-4 border-top">
            <h6 class="section-heading mb-3">
                Informasi Penyewaan
            </h6>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="d-flex gap-3">
                        <i class="bi bi-calendar-range text-primary fs-4"></i>
                        <div>
                            <div class="fw-semibold small">Durasi Fleksibel</div>
                            <div class="text-muted small">
                                Tentukan sendiri tanggal penyewaan.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex gap-3">
                        <i class="bi bi-shield-check text-primary fs-4"></i>
                        <div>
                            <div class="fw-semibold small">Transaksi Aman</div>
                            <div class="text-muted small">
                                Pembayaran diproses melalui Midtrans.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex gap-3">
                        <i class="bi bi-box-seam text-primary fs-4"></i>
                        <div>
                            <div class="fw-semibold small">Produk Berkualitas</div>
                            <div class="text-muted small">
                                Produk disediakan oleh mitra vendor Event 4U.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const startDate = document.querySelector('[name="start_date"]');
    const endDate = document.querySelector('[name="end_date"]');

    if (startDate && endDate) {
        startDate.addEventListener('change', function () {
            endDate.min = this.value;

            if (endDate.value && endDate.value < this.value) {
                endDate.value = this.value;
            }
        });
    }
</script>
@endpush
