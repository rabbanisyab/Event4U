
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Tambah Produk</h4>
        <p class="text-muted mb-0">Tambahkan perlengkapan event yang ingin disewakan.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" placeholder="Contoh: Kursi Futura">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Jelaskan detail produk...">{{ old('description') }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Harga Sewa per Hari (Rp)</label>
                        <input type="number" name="price_per_day" class="form-control"
                               value="{{ old('price_per_day') }}" min="0">
                        @error('price_per_day') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stock" class="form-control"
                               value="{{ old('stock') }}" min="0">
                        @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Produk</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status Produk</label>
                    <select name="status" class="form-select">
                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Produk
                    </button>

                    <a href="{{ route('vendor.products.index') }}" class="btn btn-light">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
