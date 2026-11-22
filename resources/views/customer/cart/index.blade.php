
@extends('layouts.public')

@section('title', 'Keranjang Saya - Event 4U')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h4 class="fw-bold">Keranjang Saya</h4>
        <p class="text-secondary small">
            Periksa perlengkapan dan tanggal penyewaan sebelum checkout.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-8">

            @forelse($cart->items as $item)
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-3">

                        <div class="row g-3 align-items-center">

                            <div class="col-4 col-md-3">
                                @if($item->product && $item->product->image)
                                    <img
                                        src="{{ asset('storage/' . $item->product->image) }}"
                                        class="img-fluid rounded-3"
                                        style="width:100%;height:120px;object-fit:cover;"
                                        alt="{{ $item->product->name }}">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center"
                                         style="height:120px;">
                                        <i class="bi bi-image text-secondary fs-2"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="col-8 col-md-9">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <small class="text-primary">
                                            {{ $item->product->category->name ?? 'Umum' }}
                                        </small>

                                        <h6 class="fw-bold mt-1 mb-1">
                                            {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                        </h6>

                                        <p class="text-primary fw-semibold mb-2">
                                            Rp{{ number_format($item->product->price_per_day ?? 0, 0, ',', '.') }}
                                            <small class="text-secondary fw-normal">/ hari</small>
                                        </p>
                                    </div>
                                </div>

                                <form action="{{ route('customer.cart.update', $item->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row g-2">
                                        <div class="col-6 col-md-3">
                                            <label class="form-label small">Jumlah</label>
                                            <input type="number"
                                                   name="quantity"
                                                   class="form-control form-control-sm"
                                                   min="1"
                                                   value="{{ $item->quantity }}"
                                                   required>
                                        </div>

                                        <div class="col-6 col-md-4">
                                            <label class="form-label small">Mulai Sewa</label>
                                            <input type="date"
                                                   name="start_date"
                                                   class="form-control form-control-sm"
                                                   value="{{ $item->start_date->format('Y-m-d') }}"
                                                   min="{{ now()->toDateString() }}"
                                                   required>
                                        </div>

                                        <div class="col-6 col-md-4">
                                            <label class="form-label small">Selesai Sewa</label>
                                            <input type="date"
                                                   name="end_date"
                                                   class="form-control form-control-sm"
                                                   value="{{ $item->end_date->format('Y-m-d') }}"
                                                   min="{{ now()->toDateString() }}"
                                                   required>
                                        </div>

                                        <div class="col-6 col-md-1 d-flex align-items-end">
                                            <button type="submit"
                                                    class="btn btn-outline-primary btn-sm w-100"
                                                    title="Perbarui">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-secondary">
                                        {{ $item->start_date->diffInDays($item->end_date) + 1 }}
                                        hari penyewaan
                                    </small>

                                    <form action="{{ route('customer.cart.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus produk dari keranjang?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-link text-danger p-0 small text-decoration-none">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-cart-x text-secondary"
                           style="font-size:45px;"></i>

                        <h6 class="fw-bold mt-3">Keranjang Masih Kosong</h6>

                        <p class="text-secondary small">
                            Yuk, cari perlengkapan event yang kamu butuhkan.
                        </p>

                        <a href="{{ route('products.index') }}"
                           class="btn btn-primary btn-sm px-4">
                            Jelajahi Produk
                        </a>
                    </div>
                </div>
            @endforelse

        </div>

        {{-- RINGKASAN --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">Ringkasan Keranjang</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary small">Total Produk</span>
                        <span class="fw-semibold">{{ $cart->items->count() }} item</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-semibold">Total Sewa</span>
                        <h5 class="fw-bold text-primary">
                            Rp{{ number_format($total, 0, ',', '.') }}
                        </h5>
                    </div>

                    <a href="{{ route('customer.checkout.index') }}"
                    class="btn btn-primary w-100">
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection