<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Tampilkan wishlist pengguna
    public function index()
    {
        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->user_id)->get();
        return response()->json($wishlists, 200);
    }

    // Tambahkan produk ke wishlist
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:products,product_id',
        ]);

        $wishlist = Wishlist::create([
            'user_id' => Auth::user()->user_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'message' => 'Product added to wishlist',
            'wishlist' => $wishlist,
        ], 201);
    }

    // Hapus produk dari wishlist
    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);

        if ($wishlist->user_id !== Auth::user()->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $wishlist->delete();

        return response()->json([
            'message' => 'Product removed from wishlist',
        ], 200);
    }
}
