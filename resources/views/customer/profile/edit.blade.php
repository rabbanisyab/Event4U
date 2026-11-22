
@extends('layouts.public')

@section('title', 'Profil Saya - Event 4U')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h4 class="fw-bold">Profil Saya</h4>
        <p class="text-secondary small">
            Kelola informasi akun Event 4U kamu.
        </p>
    </div>

    <div class="row">
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width:55px;height:55px;font-size:24px;">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                            <small class="text-secondary">Customer Event 4U</small>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success small">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $user->name) }}"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Alamat Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $user->email) }}"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>
                            Simpan Perubahan
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
