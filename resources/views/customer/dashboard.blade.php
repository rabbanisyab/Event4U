@extends('layouts.dashboard')

@section('title', 'Dashboard Customer')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Halo, {{ Auth::user()->name }}!</h3>
        <p class="text-muted">Selamat datang di Event 4U. Mau sewa perlengkapan apa hari ini?</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-receipt fs-1 text-primary"></i>
                    <div>
                        <p class="text-muted mb-1">Total Pesanan</p>
                        <h3 class="fw-bold mb-0">{{ $totalBookings }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-cart3 fs-1 text-success"></i>
                    <div>
                        <p class="text-muted mb-1">Produk di Keranjang</p>
                        <h3 class="fw-bold mb-0">{{ $totalCartItems }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-1">Produk Terbaru</h5>
            <p class="text-muted small mb-0">Temukan perlengkapan untuk acara kamu.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
            Lihat Semua
        </a>
    </div>

    <div class="row g-3">
        @forelse($latestProducts as $product)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="card-img-top"
                             style="height: 160px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center"
                             style="height: 160px;">
                            <i class="bi bi-image fs-1 text-secondary"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <small class="text-muted">{{ $product->category->name ?? 'Umum' }}</small>
                        <h6 class="fw-bold mt-1">{{ $product->name }}</h6>
                        <p class="text-primary fw-bold mb-2">
                            Rp{{ number_format($product->price_per_day, 0, ',', '.') }}/hari
                        </p>
                        <a href="{{ route('products.show', $product->id) }}"
                           class="btn btn-outline-primary btn-sm w-100">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center">
                    Belum ada produk tersedia.
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection