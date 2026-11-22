<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $cart->load('items.product.category');

        if ($cart->items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $total = 0;

        foreach ($cart->items as $item) {
            $days = $item->start_date->diffInDays($item->end_date) + 1;

            $total += $item->product->price_per_day
                * $item->quantity
                * $days;
        }

        return view('customer.checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_method' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:delivery_method,delivery|nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        try {
            $booking = DB::transaction(function () use ($items, $validated, $cart) {

                $products = Product::whereIn(
                    'id',
                    $items->pluck('product_id')->unique()
                )->lockForUpdate()->get()->keyBy('id');

                foreach ($items->groupBy('product_id') as $productId => $productItems) {
                    $product = $products->get($productId);
                    $requestedStock = $productItems->sum('quantity');

                    if (
                        !$product ||
                        $product->status !== 'available' ||
                        $product->stock < $requestedStock
                    ) {
                        throw new \RuntimeException(
                            'Stok produk ' . ($product->name ?? '') . ' tidak mencukupi.'
                        );
                    }
                }

                foreach ($items->groupBy('product_id') as $productId => $productItems) {
                    $product = $products->get($productId);
                    $requestedStock = $productItems->sum('quantity');

                    $product->decrement('stock', $requestedStock);
                }

                $total = 0;

                foreach ($items as $item) {
                    $days = $item->start_date->diffInDays($item->end_date) + 1;

                    $total += $item->product->price_per_day
                        * $item->quantity
                        * $days;
                }

                $startDate = $items->pluck('start_date')->sort()->first();
                $endDate = $items->pluck('end_date')->sortDesc()->first();

                do {
                    $bookingCode = 'EV4U-' . strtoupper(Str::random(8));
                } while (Booking::where('booking_code', $bookingCode)->exists());

                $booking = Booking::create([
                    'user_id' => Auth::id(),
                    'booking_code' => $bookingCode,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_price' => $total,
                    'status' => 'pending',
                    'delivery_method' => $validated['delivery_method'],
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    $days = $item->start_date->diffInDays($item->end_date) + 1;

                    $subtotal = $item->product->price_per_day
                        * $item->quantity
                        * $days;

                    BookingDetail::create([
                        'booking_id' => $booking->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'start_date' => $item->start_date,
                        'end_date' => $item->end_date,
                        'price_per_day' => $item->product->price_per_day,
                        'subtotal' => $subtotal,
                        'vendor_status' => 'pending',
                    ]);
                }

                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => 'midtrans',
                    'amount' => $total,
                    'status' => 'pending',
                ]);

                $cart->items()->delete();

                return $booking;
            });

        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('customer.payment.pay', $booking->id);
    }
}
