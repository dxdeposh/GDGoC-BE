<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    // Sebelum aturan spesifik dijalankan
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true; // Admin memiliki akses penuh
        }
    }

    // Tampilkan produk
    public function view(User $user, Product $product)
    {
        return true; // Semua pengguna dapat melihat produk
    }

    // Buat produk
    public function create(User $user)
    {
        return $user->role === 'seller';
    }

    // Update produk
    public function update(User $user, Product $product)
    {
        return $user->user_id === $product->user_id;
    }

    // Hapus produk
    public function delete(User $user, Product $product)
    {
        return $user->user_id === $product->user_id;
    }
}
