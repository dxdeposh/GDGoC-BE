<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    // Tampilkan semua ulasan untuk produk tertentu
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)->where('status_review', 'approved')->get();
        return response()->json($reviews, 200);
    }

    // Buat ulasan baru
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        $review = Review::create([
            'product_id' => $productId,
            'user_id' => Auth::user()->user_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'status_review' => 'pending',
        ]);

        // Tambahkan notifikasi atau log aktivitas jika diperlukan

        return response()->json([
            'message' => 'Review submitted successfully and is pending approval',
            'review' => $review,
        ], 201);
    }

    // Update ulasan (Owner atau Admin)
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $this->authorize('update', $review);

        $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
            'status_review' => ['sometimes', 'required', Rule::in(['approved', 'pending', 'rejected'])],
            'replied' => 'sometimes|boolean',
        ]);

        $review->update($request->all());

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review,
        ], 200);
    }

    // Hapus ulasan (Admin only)
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        $this->authorize('delete', $review);

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ], 200);
    }
}
