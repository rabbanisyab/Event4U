@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold">Tambah Pengguna</h4>
    <p class="text-secondary">Buat akun Customer, Vendor, atau Admin baru.</p>
</div>

<div class="dashboard-card">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="Masukkan nama lengkap">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="contoh@email.com">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Minimal 8 karakter">

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="form-control"
                   placeholder="Ulangi password">
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Role Pengguna</label>
            <select name="role" class="form-select @error('role') is-invalid @enderror">
                <option value="">Pilih Role</option>
                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="vendor" {{ old('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Simpan Pengguna
            </button>

            <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                Kembali
            </a>
        </div>
    </form>
</div>

@endsection