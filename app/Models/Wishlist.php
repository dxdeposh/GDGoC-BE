<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $primaryKey = 'wishlist_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'wishlist_id',
        'user_id',
        'product_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->wishlist_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
