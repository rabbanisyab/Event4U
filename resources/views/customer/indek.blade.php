
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Keranjang Saya</h4>
        <p class="text-muted mb-0">
            Periksa produk dan tanggal penyewaan sebelum checkout.
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
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($cart->items->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart3 display-3 text-muted"></i>
                <h5 class="fw-bold mt-3">Keranjang Masih Kosong</h5>
                <p class="text-muted">
                    Yuk, cari perlengkapan event yang kamu butuhkan.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Lihat Katalog Produk
                </a>
            </div>
        </div>
    @else

        <div class="row g-4">

            <div class="col-lg-8">
                @foreach($cart->items as $item)
                    @php
                        $days = $item->start_date->diffInDays($item->end_date) + 1;
                        $subtotal = $item->product->price_per_day * $item->quantity * $days;
                    @endphp

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">

                            <div class="d-flex gap-3">
                                <div style="width:110px; flex-shrink:0;">
                                    @if($item->product->image)
                                        <img
                                            src="{{ asset('storage/'.$item->product->image) }}"
                                            class="rounded w-100"
                                            style="height:110px; object-fit:cover;"
                                            alt="{{ $item->product->name }}"
                                        >
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="height:110px;">
                                            <i class="bi bi-image fs-2 text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                {{ $item->product->name }}
                                            </h6>
                                            <small class="text-muted">
                                                {{ $item->product->category->name ?? '-' }}
                                            </small>
                                        </div>

                                        <form action="{{ route('customer.cart.destroy', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus produk dari keranjang?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="fw-semibold text-primary mt-2">
                                        Rp{{ number_format($item->product->price_per_day, 0, ',', '.') }}
                                        <small class="text-muted fw-normal">/ hari</small>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <form action="{{ route('customer.cart.update', $item->id) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">
                                            Jumlah
                                        </label>
                                        <input type="number" name="quantity"
                                               class="form-control"
                                               min="1"
                                               max="{{ $item->product->stock }}"
                                               value="{{ $item->quantity }}"
                                               required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">
                                            Tanggal Mulai
                                        </label>
                                        <input type="date" name="start_date"
                                               class="form-control"
                                               min="{{ now()->toDateString() }}"
                                               value="{{ $item->start_date->format('Y-m-d') }}"
                                               required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">
                                            Tanggal Selesai
                                        </label>
                                        <input type="date" name="end_date"
                                               class="form-control"
                                               min="{{ now()->toDateString() }}"
                                               value="{{ $item->end_date->format('Y-m-d') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Perbarui
                                    </button>

                                    <div class="text-end">
                                        <small class="text-muted">
                                            {{ $days }} hari × {{ $item->quantity }} unit
                                        </small>
                                        <div class="fw-bold">
                                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Jumlah produk</span>
                            <span>{{ $cart->items->count() }} jenis</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold">Total Sementara</span>
                            <span class="fw-bold text-primary fs-5">
                                Rp{{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        <button class="btn btn-primary w-100" disabled>
                            Lanjut Checkout (Segera Hadir)
                        </button>

                        <a href="{{ route('products.index') }}"
                           class="btn btn-light w-100 mt-2">
                            Tambah Produk Lain
                        </a>

                    </div>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
