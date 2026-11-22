
@extends('layouts.dashboard')

@section('title', 'Ubah Role Pengguna')
@section('page-title', 'Ubah Role Pengguna')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold">Ubah Role Pengguna</h4>
    <p class="text-secondary">Atur hak akses akun pengguna.</p>
</div>

<div class="dashboard-card" style="max-width: 650px;">

    <form action="{{ route('admin.users.update', $user->id) }}"
          method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Pengguna</label>
            <input type="text" class="form-control"
                   value="{{ $user->name }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control"
                   value="{{ $user->email }}" disabled>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Role Pengguna</label>
            <select name="role" class="form-select">
                <option value="customer"
                    {{ $user->role === 'customer' ? 'selected' : '' }}>
                    Customer
                </option>
                <option value="vendor"
                    {{ $user->role === 'vendor' ? 'selected' : '' }}>
                    Vendor
                </option>
                <option value="admin"
                    {{ $user->role === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
            </select>

            @error('role')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}"
               class="btn btn-light border">
                Batal
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@endsection
