<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        $cart->load('items.product.category');

        $total = 0;

        foreach ($cart->items as $item) {
            $days = $item->start_date->diffInDays($item->end_date) + 1;
            $total += $item->product->price_per_day * $item->quantity * $days;
        }

        return view('customer.cart.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->status !== 'available' || $product->stock < $validated['quantity']) {
            return back()->with('error', 'Produk tidak tersedia atau stok tidak mencukupi.');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('customer.cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        abort_unless(
            $cartItem->cart->user_id === Auth::id(),
            403
        );

        $cartItem->load('product');

        if (
            $cartItem->product->status !== 'available' ||
            $cartItem->product->stock < $validated['quantity']
        ) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cartItem->update($validated);

        return redirect()->route('customer.cart.index')
            ->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function destroy(CartItem $cartItem)
    {
        abort_unless(
            $cartItem->cart->user_id === Auth::id(),
            403
        );

        $cartItem->delete();

        return redirect()->route('customer.cart.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}