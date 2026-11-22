
@extends('layouts.public')

@section('title', 'Event 4U - Sewa Perlengkapan Event')

@section('content')

{{-- HERO SECTION --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                    EVENT RENTAL PLATFORM
                </span>

                <h1 class="hero-title fw-bold mb-3">
                    Wujudkan Acara Impianmu,
                    <span class="text-primary">Lebih Mudah!</span>
                </h1>

                <p class="text-secondary hero-description mb-4">
                    Temukan berbagai perlengkapan event yang kamu butuhkan.
                    Mulai dari dekorasi, sound system, hingga perlengkapan
                    acara lainnya dalam satu platform.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="#produk" class="btn btn-primary rounded-pill px-4">
                        Jelajahi Produk
                    </a>

                    <a href="#tentang" class="btn btn-outline-secondary rounded-pill px-4">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <div class="d-flex gap-4 mt-4">
                    <div>
                        <h5 class="fw-bold mb-0">Mudah</h5>
                        <small class="text-secondary">Proses booking</small>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Praktis</h5>
                        <small class="text-secondary">Satu platform</small>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Lengkap</h5>
                        <small class="text-secondary">Pilihan barang</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-image-wrapper">
                    <img
                        src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1000&q=85"
                        class="hero-image"
                        alt="Suasana acara event">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SEARCH BAR --}}
<section class="search-section">
    <div class="container">
        <div class="bg-white rounded-4 shadow-sm p-3">
            <form action="{{ route('home') }}" method="GET">
                <div class="row align-items-end g-3">
                    <div class="col-lg-9">
                        <label class="form-label fw-semibold small">
                            Cari perlengkapan event
                        </label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Contoh: kursi, tenda, sound system..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-primary w-100">
                            Cari Barang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- KATEGORI --}}
<section class="content-section" id="kategori">
    <div class="container">
        <div class="section-heading">
            <span class="text-primary fw-semibold small">KATEGORI</span>
            <h2 class="section-title fw-bold mt-1">Cari Berdasarkan Kebutuhan</h2>
            <p class="text-secondary mb-0">
                Pilih kategori perlengkapan sesuai acara yang kamu adakan.
            </p>
        </div>

        @php
            $categories = [
                ['name' => 'Dekorasi', 'icon' => 'bi-balloon'],
                ['name' => 'Sound System', 'icon' => 'bi-speaker'],
                ['name' => 'Panggung', 'icon' => 'bi-mic'],
                ['name' => 'Meja & Kursi', 'icon' => 'bi-grid'],
                ['name' => 'Lighting', 'icon' => 'bi-lightbulb'],
                ['name' => 'Tenda', 'icon' => 'bi-house'],
            ];
        @endphp

        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="#produk" class="text-decoration-none text-dark">
                        <div class="category-card bg-white rounded-4 text-center shadow-sm h-100">
                            <i class="bi {{ $category['icon'] }} text-primary"></i>
                            <h6 class="fw-semibold mb-0 mt-2">
                                {{ $category['name'] }}
                            </h6>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRODUK --}}
<section class="content-section" id="produk">
    <div class="container">
        <div class="section-heading">
            <span class="text-primary fw-semibold small">PRODUK PILIHAN</span>
            <h2 class="section-title fw-bold mt-1">Perlengkapan Event</h2>
            <p class="text-secondary mb-0">
                Temukan perlengkapan untuk acara spesialmu.
            </p>
        </div>

        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('products.show', $product->id) }}"
                       class="text-decoration-none text-dark">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 product-card">
                            @if($product->image)
                                <img
                                    src="{{ str_starts_with($product->image, 'http')
                                        ? $product->image
                                        : asset('storage/' . $product->image) }}"
                                    class="product-image"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                    <span class="text-secondary small">Tidak ada gambar</span>
                                </div>
                            @endif

                            <div class="card-body p-3">
                                <small class="text-primary">
                                    {{ $product->category->name ?? 'Umum' }}
                                </small>

                                <h6 class="fw-semibold product-name mt-1 mb-2">
                                    {{ $product->name }}
                                </h6>

                                <p class="text-secondary small mb-2">
                                    Stok: {{ $product->stock }}
                                </p>

                                <div class="fw-bold text-primary product-price">
                                    Rp {{ number_format($product->price_per_day, 0, ',', '.') }}
                                    <small class="text-secondary fw-normal">/ hari</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="bg-white rounded-4 p-4 text-center shadow-sm">
                        <h6 class="fw-bold">Belum Ada Produk</h6>
                        <p class="text-secondary small mb-0">
                            Produk perlengkapan event akan ditampilkan di sini.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- TENTANG --}}
<section class="content-section" id="tentang">
    <div class="container">
        <div class="about-section bg-primary text-white rounded-4 text-center">
            <h2 class="fw-bold mb-2">
                Persiapkan Acaramu Bersama Event 4U!
            </h2>
            <p class="mb-3">
                Semua kebutuhan event, lebih mudah dalam satu platform.
            </p>
            <a href="{{ route('register') }}"
               class="btn btn-light text-primary rounded-pill px-4 py-2 fw-semibold">
                Mulai Sekarang
            </a>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="footer mt-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <h5 class="fw-bold text-white">Event4U.</h5>
                <p class="mt-2">
                    Platform penyewaan perlengkapan event
                    yang membantu mempermudah persiapan acara.
                </p>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold text-white">Navigasi</h6>
                <p><a href="#kategori" class="text-decoration-none text-secondary">Kategori</a></p>
                <p><a href="#produk" class="text-decoration-none text-secondary">Produk</a></p>
                <p><a href="#tentang" class="text-decoration-none text-secondary">Tentang Kami</a></p>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold text-white">Event 4U</h6>
                <p>Solusi kebutuhan perlengkapan event.</p>
            </div>
        </div>

        <hr class="border-secondary">

        <div class="text-center small">
            © {{ date('Y') }} Event 4U. All Rights Reserved.
        </div>
    </div>
</footer>

@endsection

@push('styles')
<style>
    .hero-section {
        padding: 55px 0 35px;
    }

    .hero-title {
        color: #0f172a;
        font-size: 36px;
        line-height: 1.3;
        letter-spacing: -0.8px;
    }

    .hero-description {
        font-size: 14px;
        line-height: 1.8;
        max-width: 500px;
    }

    .hero-section .btn {
        font-size: 13px;
        padding: 10px 20px;
    }

    .hero-image-wrapper {
        width: 100%;
        overflow: hidden;
        border-radius: 18px;
    }

    .hero-image {
        display: block;
        width: 100%;
        height: 350px;
        object-fit: cover;
    }

    .search-section {
        padding: 15px 0 25px;
    }

    .search-section .form-control {
        height: 42px;
        font-size: 13px;
    }

    .search-section .btn {
        height: 42px;
        font-size: 13px;
    }

    .content-section {
        padding: 35px 0;
    }

    .section-heading {
        margin-bottom: 22px;
    }

    .section-title {
        color: #0f172a;
        font-size: 24px;
    }

    .section-heading p {
        font-size: 13px;
    }

    .category-card {
        padding: 20px 10px;
        transition: .25s ease;
    }

    .category-card i {
        font-size: 25px;
    }

    .category-card h6 {
        font-size: 12px;
    }

    .category-card:hover,
    .product-card:hover {
        transform: translateY(-4px);
    }

    .product-card {
        transition: .25s ease;
    }

    .product-image {
        width: 100%;
        height: 175px;
        object-fit: cover;
    }

    .product-name {
        font-size: 13px;
        line-height: 1.5;
        min-height: 38px;
    }

    .product-price {
        font-size: 13px;
    }

    .about-section {
        padding: 35px 20px;
    }

    .about-section h2 {
        font-size: 23px;
    }

    .about-section p {
        font-size: 13px;
    }

    .footer {
        background: #0f172a;
        color: #adb5bd;
        padding: 35px 0 20px;
        font-size: 13px;
    }

    .footer p {
        font-size: 13px;
        margin-bottom: 9px;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 30px 0 20px;
        }

        .hero-title {
            font-size: 28px;
        }

        .hero-image {
            height: 240px;
        }

        .content-section {
            padding: 25px 0;
        }

        .section-title {
            font-size: 21px;
        }

        .product-image {
            height: 140px;
        }

        .about-section h2 {
            font-size: 20px;
        }
    }
</style>
@endpush
