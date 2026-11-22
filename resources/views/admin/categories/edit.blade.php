
@extends('layouts.dashboard')

@section('title', 'Edit Kategori')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Edit Kategori</h3>
    <p class="text-muted mb-0">Perbarui informasi kategori.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.update', $category->id) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kategori</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Saat Ini</label><br>
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}"
                         width="120" height="120"
                         class="rounded object-fit-cover mb-2">
                @else
                    <p class="text-muted">Belum ada gambar.</p>
                @endif
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Ganti Gambar</label>
                <input type="file" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
