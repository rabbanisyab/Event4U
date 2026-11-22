
@extends('layouts.dashboard')

@section('title', 'Tambah Kategori')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Tambah Kategori</h3>
    <p class="text-muted mb-0">Tambahkan kategori perlengkapan event baru.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.store') }}" method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kategori</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Contoh: Dekorasi" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Masukkan deskripsi kategori">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Gambar Kategori</label>
                <input type="file" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
