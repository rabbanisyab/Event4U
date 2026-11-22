
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Produk Saya</h4>
            <p class="text-muted mb-0">Kelola produk perlengkapan event milik Anda.</p>
        </div>

        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga / Hari</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                </td>

                                <td>{{ $product->category->name ?? '-' }}</td>

                                <td>
                                    Rp{{ number_format($product->price_per_day, 0, ',', '.') }}
                                </td>

                                <td>{{ $product->stock }}</td>

                                <td>
                                    @if($product->status === 'available')
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak tersedia</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('vendor.products.edit', $product->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('vendor.products.destroy', $product->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada produk. Silakan tambahkan produk pertama Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
