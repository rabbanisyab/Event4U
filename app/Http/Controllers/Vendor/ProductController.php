<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('vendor_id', Auth::id())
            ->latest()
            ->get();

        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('vendor.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($request->hasFile('image')) {
            $upload = cloudinary()->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'products',
                ]
            );

            $validated['image'] = $upload['secure_url'];
        }

        $validated['vendor_id'] = Auth::id();

        Product::create($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        $categories = Category::orderBy('name')->get();

        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($request->hasFile('image')) {
            $upload = cloudinary()->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'products',
                ]
            );

            $validated['image'] = $upload['secure_url'];
        }

        $product->update($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        // Hapus file lokal untuk gambar lama.
        // Gambar Cloudinary tidak dihapus pada tahap ini.
        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function authorizeProduct(Product $product)
    {
        abort_unless($product->vendor_id === Auth::id(), 403);
    }
}