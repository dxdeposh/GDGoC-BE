<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // Tampilkan semua produk atau filter berdasarkan kategori, subkategori, dll.
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        // Tambahkan filter lainnya sesuai kebutuhan

        $products = $query->get();
        return response()->json($products, 200);
    }

    // Tampilkan detail produk
    public function show($id)
    {
        $product = Product::findOrFail($id);
        // Increment views
        $product->increment('views');
        return response()->json($product, 200);
    }

    // Buat produk baru (Seller only)
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'category_id' => 'required|uuid|exists:categories,category_id',
            'sub_category_id' => 'required|uuid|exists:subcategories,sub_category_id',
            'stok' => 'required|integer|min:1',
            'kondisi' => ['required', Rule::in(['baru', 'hampir baru', 'bekas'])],
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:255',
            'garansi' => 'required|boolean',
            'estimasi_pengiriman' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gambar = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $image) {
                $path = $image->store('public/gambar_produk');
                $gambar[] = Storage::url($path);
            }
        }

        $product = Product::create([
            'user_id' => Auth::user()->user_id,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'stok' => $request->stok,
            'kondisi' => $request->kondisi,
            'brand' => $request->brand,
            'model' => $request->model,
            'warna' => $request->warna,
            'garansi' => $request->garansi,
            'estimasi_pengiriman' => $request->estimasi_pengiriman,
            'is_featured' => $request->is_featured ?? false,
            'gambar' => $gambar,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    // Update produk (Seller only)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        $request->validate([
            'nama_produk' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'harga' => 'sometimes|required|numeric',
            'category_id' => 'sometimes|required|uuid|exists:categories,category_id',
            'sub_category_id' => 'sometimes|required|uuid|exists:subcategories,sub_category_id',
            'stok' => 'sometimes|required|integer|min:1',
            'kondisi' => ['sometimes', 'required', Rule::in(['baru', 'hampir baru', 'bekas'])],
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'warna' => 'nullable|string|max:255',
            'garansi' => 'sometimes|required|boolean',
            'estimasi_pengiriman' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            foreach ($product->gambar as $gambar) {
                $path = str_replace('/storage/', 'public/', $gambar);
                Storage::delete($path);
            }
            // Upload gambar baru
            $gambar = [];
            foreach ($request->file('gambar') as $image) {
                $path = $image->store('public/gambar_produk');
                $gambar[] = Storage::url($path);
            }
            $product->gambar = $gambar;
        }

        $product->update($request->except(['gambar']));

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }

    // Hapus produk (Seller only)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('delete', $product);

        // Hapus gambar dari storage
        foreach ($product->gambar as $gambar) {
            $path = str_replace('/storage/', 'public/', $gambar);
            Storage::delete($path);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
