<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function pay($id)
    {
        $booking = Booking::with(['details.product', 'payment'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $payment = $booking->payment;

        if (!$payment) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($payment->status === 'verified') {
            return redirect()->route('customer.orders.index')
                ->with('success', 'Pembayaran sudah berhasil.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) $payment->amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => $booking->details->map(function ($detail) {
                $days = $detail->start_date->diffInDays($detail->end_date) + 1;

                return [
                    'id' => (string) $detail->product_id,
                    'price' => (int) $detail->price_per_day,
                    'quantity' => (int) ($detail->quantity * $days),
                    'name' => substr($detail->product->name, 0, 50),
                ];
            })->values()->toArray(),
        ];

        try {
            $snap = Snap::createTransaction($params);

            $payment->update([
                'transaction_id' => $booking->booking_code,
                'snap_token' => $snap->token,
                'payment_url' => $snap->redirect_url,
                'payment_method' => 'midtrans',
            ]);

            return view('customer.payment.index', compact('booking', 'snap'));

        } catch (\Exception $e) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function notification(Request $request)
    {
        $serverKey = config('midtrans.server_key');

        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if (!hash_equals($expectedSignature, $signatureKey)) {
            return response()->json([
                'message' => 'Invalid signature'
            ], 403);
        }

        $payment = Payment::where('transaction_id', $orderId)
            ->orWhereHas('booking', function ($query) use ($orderId) {
                $query->where('booking_code', $orderId);
            })
            ->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found'
            ], 404);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        if (
            $transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept')
        ) {
            $payment->update([
                'status' => 'verified'
            ]);

            $payment->booking->update([
                'status' => 'confirmed'
            ]);
        } elseif (
            in_array($transactionStatus, ['deny', 'cancel', 'expire'])
        ) {
            $payment->update([
                'status' => 'rejected'
            ]);
        }

        return response()->json([
            'message' => 'Notification processed'
        ]);
    }

    public function success($id)
    {
        $booking = Booking::with('payment')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customer.payment.success', compact('booking'));
    }
}
