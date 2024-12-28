<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar pengguna (Admin only)
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::all();
        return response()->json($users, 200);
    }

    // Tampilkan detail pengguna
    public function show($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('view', $user);

        return response()->json($user, 200);
    }

    // Update pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('update', $user);

        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->user_id, 'user_id'),
            ],
            'password' => 'sometimes|nullable|string|min:6|confirmed',
            'alamat' => 'sometimes|required|string',
            'no_telepon' => 'sometimes|required|string',
            'role' => ['sometimes', 'required', Rule::in(['buyer', 'seller', 'admin', 'moderator'])],
            'status_verifikasi' => ['sometimes', 'required', Rule::in(['verified', 'unverified', 'pending'])],
            'rating' => 'sometimes|numeric',
            'total_transaksi' => 'sometimes|integer',
            'lokasi_geolokasi_latitude' => 'sometimes|numeric',
            'lokasi_geolokasi_longitude' => 'sometimes|numeric',
        ]);

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update($request->except(['password']));

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

    // Hapus pengguna (Admin only)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }
}
