
@extends('layouts.dashboard')

@section('title', 'Data Pesanan')
@section('page-title', 'Data Pesanan')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">Semua Pesanan</h4>
    <p class="text-secondary mb-0">Pantau seluruh transaksi penyewaan di Event 4U.</p>
</div>

<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal Sewa</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td class="fw-semibold">
                            {{ $booking->booking_code }}
                        </td>

                        <td>
                            {{ $booking->user->name ?? '-' }}
                        </td>

                        <td>
                            {{ $booking->start_date?->format('d M Y') ?? '-' }}
                            -
                            {{ $booking->end_date?->format('d M Y') ?? '-' }}
                        </td>

                        <td>
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </td>

                        <td>
                            @php
                                $paymentStatus = $booking->payment->status ?? 'pending';
                            @endphp

                            <span class="badge bg-{{ $paymentStatus === 'verified' ? 'success' : ($paymentStatus === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </td>

                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'primary',
                                    'ongoing' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp

                            <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            Belum ada pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>
</div>

@endsection
