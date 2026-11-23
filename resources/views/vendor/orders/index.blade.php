
@extends('layouts.dashboard')

@section('title', 'Pesanan Masuk - Event 4U')

@section('content')
<div class="container-fluid py-3">

    @php
        $allDetails = $bookings->flatMap(fn($booking) => $booking->details);

        $statusCounts = [
            'pending' => $allDetails->where('vendor_status', 'pending')->count(),
            'confirmed' => $allDetails->where('vendor_status', 'confirmed')->count(),
            'ongoing' => $allDetails->where('vendor_status', 'ongoing')->count(),
            'completed' => $allDetails->where('vendor_status', 'completed')->count(),
            'rejected' => $allDetails->where('vendor_status', 'rejected')->count(),
        ];

        $statusLabels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'ongoing' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
        ];

        $statusColors = [
            'pending' => 'warning',
            'confirmed' => 'primary',
            'ongoing' => 'info',
            'completed' => 'success',
            'rejected' => 'danger',
        ];
    @endphp

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pesanan Masuk</h4>
            <p class="text-muted mb-0">
                Kelola dan pantau pesanan dari customer untuk produk kamu.
            </p>
        </div>

        <div class="order-total-badge">
            <i class="bi bi-receipt me-2"></i>
            {{ $bookings->count() }} Pesanan
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="fw-semibold mb-1">Gagal memperbarui status:</div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- RINGKASAN STATUS --}}
    <div class="row g-3 mb-4">
        @php
            $summaryCards = [
                ['label' => 'Menunggu', 'value' => $statusCounts['pending'], 'icon' => 'bi-clock', 'color' => 'warning'],
                ['label' => 'Dikonfirmasi', 'value' => $statusCounts['confirmed'], 'icon' => 'bi-check-circle', 'color' => 'primary'],
                ['label' => 'Berjalan', 'value' => $statusCounts['ongoing'], 'icon' => 'bi-arrow-repeat', 'color' => 'info'],
                ['label' => 'Selesai', 'value' => $statusCounts['completed'], 'icon' => 'bi-check-circle-fill', 'color' => 'success'],
                ['label' => 'Ditolak', 'value' => $statusCounts['rejected'], 'icon' => 'bi-x-circle', 'color' => 'danger'],
            ];
        @endphp

        @foreach($summaryCards as $card)
            <div class="col-6 col-md-4 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <p class="text-muted small mb-1">{{ $card['label'] }}</p>
                                <h4 class="fw-bold mb-0">{{ $card['value'] }}</h4>
                            </div>
                            <div class="summary-icon text-{{ $card['color'] }} bg-{{ $card['color'] }}-subtle">
                                <i class="bi {{ $card['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- DAFTAR PESANAN --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-1">Daftar Pesanan</h5>
            <p class="text-muted small mb-0">
                Detail pesanan yang melibatkan produk kamu.
            </p>
        </div>
    </div>

    @forelse($bookings as $booking)

        @php
            $vendorTotal = $booking->details->sum('subtotal');
            $bookingStatuses = $booking->details->pluck('vendor_status')->unique();
        @endphp

        <div class="card border-0 shadow-sm mb-4 order-card">
            <div class="card-body p-4">

                {{-- HEADER PESANAN --}}
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                            <h6 class="fw-bold mb-0">{{ $booking->booking_code }}</h6>
                            <span class="badge bg-light text-dark border">
                                {{ $booking->details->count() }} Produk
                            </span>
                        </div>

                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            Dipesan {{ $booking->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>

                    <div class="text-md-end">
                        <small class="text-muted d-block">Total produk kamu</small>
                        <h5 class="fw-bold text-primary mb-0">
                            Rp{{ number_format($vendorTotal, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>

                <hr class="my-3">

                {{-- INFORMASI CUSTOMER --}}
                <div class="customer-info p-3 rounded-3 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="customer-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Customer</small>
                            <div class="fw-semibold">
                                {{ $booking->user->name ?? '-' }}
                            </div>
                            <small class="text-muted">
                                {{ $booking->user->email ?? '-' }}
                            </small>
                        </div>
                    </div>
                </div>

                {{-- INFORMASI PENYEWAAN --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="info-box">
                            <small class="text-muted d-block mb-1">Tanggal Mulai</small>
                            <div class="fw-semibold">
                                <i class="bi bi-calendar-event text-primary me-1"></i>
                                {{ $booking->start_date?->format('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <small class="text-muted d-block mb-1">Tanggal Selesai</small>
                            <div class="fw-semibold">
                                <i class="bi bi-calendar-check text-primary me-1"></i>
                                {{ $booking->end_date?->format('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <small class="text-muted d-block mb-1">Metode Pengambilan</small>
                            <div class="fw-semibold">
                                <i class="bi bi-truck text-primary me-1"></i>
                                {{ $booking->delivery_method === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($booking->delivery_method === 'delivery' && $booking->delivery_address)
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Alamat Pengiriman</small>
                        <div class="small">{{ $booking->delivery_address }}</div>
                    </div>
                @endif

                @if($booking->notes)
                    <div class="mb-4">
                        <small class="text-muted d-block mb-1">Catatan Customer</small>
                        <div class="small">{{ $booking->notes }}</div>
                    </div>
                @endif

                <hr>

                {{-- DETAIL PRODUK --}}
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    Produk yang Dipesan
                </h6>

                @foreach($booking->details as $detail)
                    @php
                        $status = $detail->vendor_status;
                    @endphp

                    <div class="product-order p-3 rounded-3 mb-3">
                        <div class="row align-items-center g-3">

                            <div class="col-lg">
                                <div class="fw-semibold mb-1">
                                    {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                                </div>

                                <small class="text-muted d-block">
                                    Kategori:
                                    {{ $detail->product->category->name ?? '-' }}
                                </small>

                                <div class="d-flex flex-wrap gap-3 mt-2 small">
                                    <span>
                                        <i class="bi bi-box me-1 text-muted"></i>
                                        {{ $detail->quantity }} unit
                                    </span>

                                    <span>
                                        <i class="bi bi-tag me-1 text-muted"></i>
                                        Rp{{ number_format($detail->price_per_day, 0, ',', '.') }}/hari
                                    </span>
                                </div>
                            </div>

                            <div class="col-lg-auto">
                                <small class="text-muted d-block">Subtotal</small>
                                <div class="fw-bold">
                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="d-flex flex-column align-items-lg-end gap-2">
                                    <span class="badge rounded-pill bg-{{ $statusColors[$status] ?? 'secondary' }}-subtle text-{{ $statusColors[$status] ?? 'secondary' }} px-3 py-2">
                                        {{ $statusLabels[$status] ?? $status }}
                                    </span>

                                    {{-- AKSI STATUS --}}
                                    @if($status === 'pending')
                                        <form action="{{ route('vendor.orders.updateStatus', $detail->id) }}"
                                              method="POST"
                                              class="d-flex flex-wrap gap-2">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    name="vendor_status"
                                                    value="confirmed"
                                                    class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg me-1"></i>
                                                Konfirmasi
                                            </button>

                                            <button type="submit"
                                                    name="vendor_status"
                                                    value="rejected"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Yakin ingin menolak pesanan ini?')">
                                                <i class="bi bi-x-lg me-1"></i>
                                                Tolak
                                            </button>
                                        </form>

                                    @elseif($status === 'confirmed')
                                        <form action="{{ route('vendor.orders.updateStatus', $detail->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    name="vendor_status"
                                                    value="ongoing"
                                                    class="btn btn-sm btn-primary">
                                                <i class="bi bi-play-fill me-1"></i>
                                                Mulai Penyewaan
                                            </button>
                                        </form>

                                    @elseif($status === 'ongoing')
                                        <form action="{{ route('vendor.orders.updateStatus', $detail->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    name="vendor_status"
                                                    value="completed"
                                                    class="btn btn-sm btn-success"
                                                    onclick="return confirm('Tandai pesanan ini sudah selesai?')">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Selesaikan Pesanan
                                            </button>
                                        </form>

                                    @else
                                        <small class="text-muted">
                                            <i class="bi bi-lock me-1"></i>
                                            Tidak ada aksi
                                        </small>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

                {{-- RINGKASAN STATUS BOOKING --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                    <small class="text-muted">
                        Status produk dalam pesanan ini:
                        {{ $bookingStatuses->count() }} jenis status
                    </small>

                    <a href="{{ route('vendor.orders.index') }}"
                       class="text-decoration-none small">
                        <i class="bi bi-arrow-up me-1"></i>
                        Kembali ke daftar pesanan
                    </a>
                </div>

            </div>
        </div>

    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="empty-icon mb-3">
                    <i class="bi bi-inbox"></i>
                </div>
                <h5 class="fw-bold">Belum Ada Pesanan</h5>
                <p class="text-muted mb-0">
                    Pesanan untuk produk kamu akan muncul di halaman ini.
                </p>
            </div>
        </div>
    @endforelse

</div>
@endsection

@push('styles')
<style>
    .order-total-badge {
        background: #eff6ff;
        color: #2563eb;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .order-card {
        border-radius: 14px;
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        font-size: 19px;
    }

    .customer-info {
        background: #f8fafc;
    }

    .customer-avatar {
        width: 43px;
        height: 43px;
        border-radius: 50%;
        background: #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 10px;
        padding: 13px;
        height: 100%;
    }

    .product-order {
        background: #f8fafc;
        border: 1px solid #edf0f4;
    }

    .empty-icon {
        font-size: 48px;
        color: #94a3b8;
    }

    .order-card .badge {
        font-weight: 600;
    }
</style>
@endpush
