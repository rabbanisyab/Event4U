
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Pesanan Masuk</h4>
        <p class="text-muted mb-0">
            Kelola pesanan yang masuk untuk produk Anda.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse($bookings as $booking)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">
                            {{ $booking->booking_code }}
                        </h6>
                        <small class="text-muted">
                            {{ $booking->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>

                    <span class="badge bg-primary align-self-start">
                        Pesanan
                    </span>
                </div>

                <hr>

                <div class="mb-3">
                    <div class="fw-semibold">Informasi Customer</div>
                    <div>{{ $booking->user->name ?? '-' }}</div>
                    <small class="text-muted">
                        {{ $booking->user->email ?? '-' }}
                    </small>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Tanggal Mulai</small>
                        <div class="fw-semibold">
                            {{ $booking->start_date?->format('d M Y') ?? $booking->start_date }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Tanggal Selesai</small>
                        <div class="fw-semibold">
                            {{ $booking->end_date?->format('d M Y') ?? $booking->end_date }}
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">Produk yang Dipesan</h6>

                @foreach($booking->details as $detail)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between flex-wrap gap-3">
                            <div>
                                <div class="fw-semibold">
                                    {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                                </div>

                                <small class="text-muted">
                                    Kategori:
                                    {{ $detail->product->category->name ?? '-' }}
                                </small>

                                <div class="mt-2">
                                    Jumlah: {{ $detail->quantity }}
                                </div>

                                <div>
                                    Subtotal:
                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="text-md-end">
                                <div class="mb-2">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Menunggu',
                                            'confirmed' => 'Dikonfirmasi',
                                            'rejected' => 'Ditolak',
                                            'ongoing' => 'Sedang Berlangsung',
                                            'completed' => 'Selesai',
                                        ];

                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'primary',
                                            'rejected' => 'danger',
                                            'ongoing' => 'info',
                                            'completed' => 'success',
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $statusColors[$detail->vendor_status] ?? 'secondary' }}">
                                        {{ $statusLabels[$detail->vendor_status] ?? $detail->vendor_status }}
                                    </span>
                                </div>

                                <form action="{{ route('vendor.orders.updateStatus', $detail->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <select name="vendor_status"
                                            class="form-select form-select-sm mb-2">
                                        <option value="confirmed"
                                            {{ $detail->vendor_status == 'confirmed' ? 'selected' : '' }}>
                                            Konfirmasi
                                        </option>

                                        <option value="rejected"
                                            {{ $detail->vendor_status == 'rejected' ? 'selected' : '' }}>
                                            Tolak
                                        </option>

                                        <option value="ongoing"
                                            {{ $detail->vendor_status == 'ongoing' ? 'selected' : '' }}>
                                            Sedang Berlangsung
                                        </option>

                                        <option value="completed"
                                            {{ $detail->vendor_status == 'completed' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        Simpan Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <h5 class="fw-bold mt-3">Belum Ada Pesanan</h5>
                <p class="text-muted mb-0">
                    Pesanan untuk produk Anda akan muncul di halaman ini.
                </p>
            </div>
        </div>
    @endforelse

</div>
@endsection
