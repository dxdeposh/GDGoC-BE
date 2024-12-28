<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'buyer_id',
        'seller_id',
        'product_id',
        'jumlah',
        'total_harga',
        'status_order',
        'alamat_pengiriman',
        'kode_resi',
        'metode_pengiriman',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->order_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'order_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'order_id', 'order_id');
    }
}
