<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'user_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'category_id',
        'sub_category_id',
        'stok',
        'status',
        'gambar',
        'lokasi_geolokasi_latitude',
        'lokasi_geolokasi_longitude',
        'kondisi',
        'brand',
        'model',
        'warna',
        'garansi',
        'estimasi_pengiriman',
        'is_featured',
        'views',
        'likes',
    ];

    protected $casts = [
        'gambar' => 'array',
        'garansi' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->product_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id', 'sub_category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    // Tambahkan relasi lainnya sesuai kebutuhan
}
