
@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold">Selamat Datang, {{ auth()->user()->name }}!</h4>
    <p class="text-secondary">Berikut ringkasan aktivitas Event 4U.</p>
</div>

<div class="row g-4">

    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Total Produk</p>
                    <h3 class="fw-bold">{{ $totalProducts }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Total Kategori</p>
                    <h3 class="fw-bold">{{ $totalCategories }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Total Pesanan</p>
                    <h3 class="fw-bold">{{ $totalBookings }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Pesanan Pending</p>
                    <h3 class="fw-bold">{{ $totalPending }}</h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="dashboard-card mt-4">
    <h5 class="fw-bold mb-2">Pengelolaan Event 4U</h5>
    <p class="text-secondary">Gunakan menu sidebar untuk mengelola sistem.</p>
</div>

@endsection
