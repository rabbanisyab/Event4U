
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

{{-- TENTANG KAMI --}}
<section class="content-section" id="tentang">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="text-primary fw-semibold small">TENTANG KAMI</span>
                <h2 class="section-title fw-bold mt-2 mb-3">
                    Mengenal Lebih Dekat Event 4U
                </h2>

                <p class="text-secondary" style="line-height: 1.9;">
                    Event 4U merupakan platform penyewaan perlengkapan
                    event yang menghubungkan penyedia barang dengan
                    pelanggan dalam satu tempat.
                </p>

                <p class="text-secondary" style="line-height: 1.9;">
                    Kami hadir untuk membantu mempermudah proses pencarian
                    dan penyewaan berbagai kebutuhan acara, mulai dari
                    dekorasi, sound system, hingga perlengkapan lainnya.
                </p>

                <p class="text-secondary" style="line-height: 1.9;">
                    Dengan Event 4U, pelanggan dapat menemukan produk,
                    melihat informasi harga dan stok, serta melakukan
                    pemesanan secara online dengan lebih praktis.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="about-highlight rounded-4 p-4 p-md-5">
                    <div class="about-icon mb-3">
                        <i class="bi bi-calendar2-event"></i>
                    </div>
                    <h4 class="fw-bold mb-3">
                        Semua Kebutuhan Event dalam Satu Platform
                    </h4>
                    <p class="text-secondary mb-0">
                        Kami ingin membuat persiapan acara menjadi
                        lebih mudah dengan menyediakan akses ke berbagai
                        pilihan perlengkapan event secara online.
                    </p>
                </div>
            </div>
        </div>

        {{-- KEUNGGULAN --}}
        <div class="row g-3 mt-4">
            <div class="col-md-4">
                <div class="feature-card h-100">
                    <i class="bi bi-search text-primary"></i>
                    <h6 class="fw-bold mt-3">Mudah Dicari</h6>
                    <p class="text-secondary small mb-0">
                        Temukan berbagai perlengkapan event berdasarkan
                        kategori dan kebutuhan acara.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card h-100">
                    <i class="bi bi-clock text-primary"></i>
                    <h6 class="fw-bold mt-3">Lebih Praktis</h6>
                    <p class="text-secondary small mb-0">
                        Cari produk dan lakukan pemesanan melalui
                        satu platform secara online.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card h-100">
                    <i class="bi bi-box-seam text-primary"></i>
                    <h6 class="fw-bold mt-3">Pilihan Beragam</h6>
                    <p class="text-secondary small mb-0">
                        Berbagai kebutuhan acara tersedia dari
                        vendor yang tergabung di Event 4U.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CARA KERJA --}}
<section class="content-section bg-white" id="cara-kerja">
    <div class="container">
        <div class="section-heading text-center">
            <span class="text-primary fw-semibold small">CARA KERJA</span>
            <h2 class="section-title fw-bold mt-1">
                Sewa Perlengkapan dengan Mudah
            </h2>
            <p class="text-secondary">
                Ikuti beberapa langkah sederhana untuk memesan perlengkapan event.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-6 col-lg-3">
                <div class="step-card text-center h-100">
                    <div class="step-number">01</div>
                    <i class="bi bi-search"></i>
                    <h6 class="fw-bold mt-3">Cari Produk</h6>
                    <p class="text-secondary small mb-0">
                        Pilih perlengkapan sesuai kebutuhan acaramu.
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="step-card text-center h-100">
                    <div class="step-number">02</div>
                    <i class="bi bi-cart-plus"></i>
                    <h6 class="fw-bold mt-3">Buat Pesanan</h6>
                    <p class="text-secondary small mb-0">
                        Masukkan produk ke keranjang dan tentukan tanggal sewa.
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="step-card text-center h-100">
                    <div class="step-number">03</div>
                    <i class="bi bi-credit-card"></i>
                    <h6 class="fw-bold mt-3">Lakukan Pembayaran</h6>
                    <p class="text-secondary small mb-0">
                        Selesaikan pembayaran sesuai instruksi yang tersedia.
                    </p>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="step-card text-center h-100">
                    <div class="step-number">04</div>
                    <i class="bi bi-check-circle"></i>
                    <h6 class="fw-bold mt-3">Pesanan Diproses</h6>
                    <p class="text-secondary small mb-0">
                        Pantau status pesanan hingga proses penyewaan selesai.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KONTAK --}}
<section class="content-section" id="kontak">
    <div class="container">
        <div class="section-heading">
            <span class="text-primary fw-semibold small">HUBUNGI KAMI</span>
            <h2 class="section-title fw-bold mt-1">
                Ada Pertanyaan?
            </h2>
            <p class="text-secondary">
                Jangan ragu untuk menghubungi tim Event 4U jika membutuhkan bantuan.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="contact-info-card h-100">
                    <h5 class="fw-bold mb-3">Kontak Event 4U</h5>

                    <div class="d-flex gap-3 mb-4">
                        <div class="contact-icon">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">WhatsApp</h6>
                            <p class="text-secondary small mb-0">
                                Hubungi admin untuk pertanyaan dan bantuan.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="contact-icon">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Layanan Online</h6>
                            <p class="text-secondary small mb-0">
                                Event 4U dapat diakses secara online
                                tanpa perlu datang ke kantor fisik.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="contact-icon">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Pusat Bantuan</h6>
                            <p class="text-secondary small mb-0">
                                Sampaikan pertanyaan atau kendala
                                terkait penyewaan perlengkapan event.
                            </p>
                        </div>
                    </div>

                    <a href="https://wa.me/628xxxxxxxxxx"
                       target="_blank"
                       class="btn btn-success mt-4 w-100">
                        <i class="bi bi-whatsapp me-2"></i>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="contact-form-card">
                    <h5 class="fw-bold mb-1">Kirim Pertanyaan</h5>
                    <p class="text-secondary small mb-4">
                        Isi formulir berikut untuk mengirim pesan ke admin.
                    </p>

                    <form id="contactForm">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Nama
                            </label>
                            <input type="text"
                                   id="contactName"
                                   class="form-control"
                                   placeholder="Masukkan nama kamu"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Email
                            </label>
                            <input type="email"
                                   id="contactEmail"
                                   class="form-control"
                                   placeholder="nama@email.com"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Pesan
                            </label>
                            <textarea id="contactMessage"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Tuliskan pertanyaan kamu..."
                                      required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-2"></i>
                            Kirim Pertanyaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="footer mt-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-5">
                <h5 class="fw-bold text-white">Event 4U<span class="text-primary">.</span></h5>
                <p class="mt-2">
                    Platform penyewaan perlengkapan event yang
                    membantu menghubungkan pelanggan dengan vendor
                    untuk memenuhi kebutuhan acara.
                </p>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold text-white mb-3">Navigasi</h6>
                <p><a href="#kategori" class="footer-link">Kategori</a></p>
                <p><a href="#produk" class="footer-link">Katalog Produk</a></p>
                <p><a href="#tentang" class="footer-link">Tentang Kami</a></p>
                <p><a href="#cara-kerja" class="footer-link">Cara Kerja</a></p>
                <p><a href="#kontak" class="footer-link">Kontak</a></p>
            </div>

            <div class="col-md-4">
                <h6 class="fw-bold text-white mb-3">Hubungi Kami</h6>
                <p>
                    <i class="bi bi-globe me-2"></i>
                    Platform penyewaan online
                </p>
                <p>
                    <i class="bi bi-whatsapp me-2"></i>
                    WhatsApp Admin Event 4U
                </p>
                <a href="#kontak" class="footer-link">
                    Hubungi tim kami
                </a>
            </div>
        </div>

        <hr class="border-secondary mt-4">

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

    html {
        scroll-behavior: smooth;
    }

    #tentang,
    #cara-kerja,
    #kontak {
        scroll-margin-top: 90px;
    }

    .about-highlight,
    .contact-info-card,
    .contact-form-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 25px;
    }

    .about-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .feature-card,
    .step-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        padding: 23px;
    }

    .feature-card > i,
    .step-card > i {
        font-size: 27px;
    }

    .step-number {
        color: #2563eb;
        font-weight: 800;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .contact-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .contact-form-card .form-control {
        font-size: 13px;
        padding: 11px 13px;
        border-radius: 8px;
    }

    .footer-link {
        color: #adb5bd;
        text-decoration: none;
    }

    .footer-link:hover {
        color: white;
    }

    @media (max-width: 768px) {
        .about-highlight,
        .contact-info-card,
        .contact-form-card,
        .feature-card,
        .step-card {
            padding: 18px;
        }
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


@push('scripts')
<script>
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        // GANTI dengan nomor WhatsApp kamu.
        // Gunakan kode negara 62, tanpa tanda + atau spasi.
        const whatsapp = '628xxxxxxxxxx';

        const nama = document.getElementById('contactName').value;
        const email = document.getElementById('contactEmail').value;
        const pesan = document.getElementById('contactMessage').value;

        const text = `Halo Admin Event 4U!

Nama: ${nama}
Email: ${email}

Pertanyaan:
${pesan}`;

        const url = `https://wa.me/${whatsapp}?text=${encodeURIComponent(text)}`;

        window.open(url, '_blank');
    });
</script>
@endpush
