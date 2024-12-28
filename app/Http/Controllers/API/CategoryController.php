<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Tampilkan semua kategori
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    // Tampilkan detail kategori
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category, 200);
    }

    // Buat kategori baru (Admin only)
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'parent_category_id' => 'nullable|uuid|exists:categories,category_id',
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    // Update kategori (Admin only)
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $this->authorize('update', $category);

        $request->validate([
            'nama_kategori' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'parent_category_id' => 'nullable|uuid|exists:categories,category_id',
        ]);

        $category->update($request->all());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category,
        ], 200);
    }

    // Hapus kategori (Admin only)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $this->authorize('delete', $category);

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
