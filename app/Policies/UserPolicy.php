<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // Sebelum aturan spesifik dijalankan
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true; // Admin memiliki akses penuh
        }
    }

    // Tampilkan daftar pengguna
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    // Tampilkan detail pengguna
    public function view(User $user, User $model)
    {
        return $user->user_id === $model->user_id;
    }

    // Update pengguna
    public function update(User $user, User $model)
    {
        return $user->user_id === $model->user_id;
    }

    // Hapus pengguna
    public function delete(User $user, User $model)
    {
        return $user->user_id !== $model->user_id; // Admin dapat menghapus, sudah di-handle di before
    }
}
