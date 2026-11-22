@extends('layouts.dashboard')

@section('title', 'Dashboard Vendor - Event 4U')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h3 class="fw-bold">Dashboard Vendor</h3>
        <p class="text-muted">
            Selamat datang, {{ auth()->user()->name }}!
            Kelola perlengkapan event yang kamu sewakan di sini.
        </p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <p class="text-muted mb-2">Total Produk</p>
                <h2 class="fw-bold">{{ $totalProducts }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <p class="text-muted mb-2">Produk Tersedia</p>
                <h2 class="fw-bold text-success">{{ $totalAvailable }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4">
                <p class="text-muted mb-2">Pesanan Produk</p>
                <h2 class="fw-bold text-primary">{{ $totalBookings }}</h2>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <h5 class="fw-bold">Kelola Produk</h5>
        <p class="text-muted">
            Tambahkan dan kelola perlengkapan event yang kamu sewakan.
        </p>

        <a href="#" class="btn btn-primary align-self-start">
            Kelola Produk
        </a>
    </div>
</div>
@endsection
