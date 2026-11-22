
@extends('layouts.dashboard')

@section('title', 'Tambah Produk')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Tambah Produk</h3>
    <p class="text-muted mb-0">Tambahkan perlengkapan event ke katalog Event 4U.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Contoh: Speaker Aktif 15 Inch" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required>
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Jelaskan detail produk">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Harga Sewa per Hari (Rp)</label>
                    <input type="number" name="price_per_day" min="0"
                           class="form-control @error('price_per_day') is-invalid @enderror"
                           value="{{ old('price_per_day') }}"
                           placeholder="Contoh: 150000" required>
                    @error('price_per_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Stok</label>
                    <input type="number" name="stock" min="0"
                           class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', 1) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Produk</label>
                <input type="file" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="available"
                        {{ old('status', 'available') == 'available' ? 'selected' : '' }}>
                        Tersedia
                    </option>
                    <option value="unavailable"
                        {{ old('status') == 'unavailable' ? 'selected' : '' }}>
                        Tidak Tersedia
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection