
@extends('layouts.public')

@section('content')

<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold">Katalog Perlengkapan Event</h2>
        <p class="text-secondary">
            Temukan perlengkapan terbaik untuk menyukseskan acaramu.
        </p>
    </div>

    {{-- Search --}}
    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text"
                   name="search"
                   class="form-control py-3"
                   placeholder="Cari perlengkapan event..."
                   value="{{ request('search') }}">

            <button class="btn btn-primary px-4">
                Cari
            </button>
        </div>
    </form>

    <div class="row">

        {{-- Category --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="fw-bold mb-3">Kategori</h5>

                <a href="{{ route('products.index') }}"
                   class="text-decoration-none mb-3">
                    Semua Produk
                </a>

                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}"
                       class="text-decoration-none text-dark mb-3">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Product List --}}
        <div class="col-lg-9">
            <div class="row g-4">

                @forelse($products as $product)

                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 product-card">

                            @if($product->image)
                                <img
                                    src="{{ str_starts_with($product->image, 'http')
                                        ? $product->image
                                        : asset('storage/' . $product->image) }}"
                                    class="card-img-top"
                                    alt="{{ $product->name }}"
                                    style="height: 190px; object-fit: cover;"
                                >
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height: 190px;">
                                    <span class="text-secondary">Tidak ada gambar</span>
                                </div>
                            @endif

                            <div class="card-body">
                                <small class="text-primary">
                                    {{ $product->category->name ?? 'Umum' }}
                                </small>

                                <h5 class="fw-bold mt-2">
                                    {{ $product->name }}
                                </h5>

                                <p class="text-secondary small">
                                    Stok tersedia: {{ $product->stock }}
                                </p>

                                <h6 class="text-primary fw-bold">
                                    Rp{{ number_format($product->price_per_day, 0, ',', '.') }}
                                    <small class="text-secondary fw-normal">/ hari</small>
                                </h6>

                                <a href="{{ route('products.show', $product->id) }}"
                                   class="btn btn-outline-primary w-100 mt-2">
                                    Lihat Detail
                                </a>
                            </div>

                        </div>
                    </div>

                @empty

                    <div class="col-12 text-center py-5">
                        <h5>Produk tidak ditemukan</h5>
                        <p class="text-secondary">
                            Coba gunakan kata kunci atau kategori lain.
                        </p>
                    </div>

                @endforelse

            </div>
        </div>

    </div>
</div>

@endsection
