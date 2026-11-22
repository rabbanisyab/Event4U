
@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Edit Produk</h3>
    <p class="text-muted mb-0">Perbarui informasi produk perlengkapan event.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.update', $product->id) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Harga Sewa per Hari (Rp)</label>
                    <input type="number" name="price_per_day" min="0"
                           class="form-control @error('price_per_day') is-invalid @enderror"
                           value="{{ old('price_per_day', $product->price_per_day) }}" required>
                    @error('price_per_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Stok</label>
                    <input type="number" name="stock" min="0"
                           class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', $product->stock) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         width="130" height="130"
                         class="rounded object-fit-cover mb-2">
                @else
                    <p class="text-muted">Belum ada gambar.</p>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ganti Gambar</label>
                <input type="file" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="available"
                        {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>
                        Tersedia
                    </option>
                    <option value="unavailable"
                        {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>
                        Tidak Tersedia
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
