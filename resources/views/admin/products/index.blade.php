
@extends('layouts.dashboard')

@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Produk</h3>
        <p class="text-muted mb-0">Kelola seluruh produk perlengkapan event.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
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
                            <div class="d-flex align-items-center gap-2">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         width="50" height="50"
                                         class="rounded object-fit-cover">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width:50px;height:50px">
                                        <i class="bi bi-image text-muted fs-4"></i>
                                    </div>
                                @endif
                                <span class="fw-semibold">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>Rp{{ number_format($product->price_per_day, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if($product->status === 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada produk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
