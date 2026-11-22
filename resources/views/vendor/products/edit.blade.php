
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Produk</h4>
        <p class="text-muted mb-0">Perbarui informasi produk Anda.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('vendor.products.update', $product->id) }}"
                  method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $product->name) }}">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Harga Sewa per Hari (Rp)</label>
                        <input type="number" name="price_per_day" class="form-control"
                               value="{{ old('price_per_day', $product->price_per_day) }}" min="0">
                        @error('price_per_day') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stock" class="form-control"
                               value="{{ old('stock', $product->stock) }}" min="0">
                        @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Produk</label>

                    @if($product->image)
                        <div class="mb-3">
                            <p class="text-muted small mb-2">Foto saat ini:</p>

                            <img
                                id="currentImage"
                                src="{{ str_starts_with($product->image, 'http')
                                    ? $product->image
                                    : asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                style="width:130px; height:130px; object-fit:cover; border-radius:10px;">
                        </div>
                    @endif

                    <input type="file" name="image" class="form-control"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewImage(event)">

                    <div class="mt-3">
                        <img id="imagePreview"
                             src="#"
                             alt="Preview Foto Baru"
                             style="display:none; width:130px; height:130px; object-fit:cover; border-radius:10px;">
                    </div>

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti foto.
                    </small>

                    @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status Produk</label>
                    <select name="status" class="form-select">
                        <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
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

@push('scripts')
<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endpush