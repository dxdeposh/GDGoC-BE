<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'password',
        'alamat',
        'no_telepon',
        'role',
        'foto_profil',
        'status_verifikasi',
        'rating',
        'total_transaksi',
        'lokasi_geolokasi_latitude',
        'lokasi_geolokasi_longitude',
    ];

    protected $hidden = [
        'password',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id', 'user_id');
    }

    // Tambahkan relasi lainnya sesuai kebutuhan
}
