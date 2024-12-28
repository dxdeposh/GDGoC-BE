<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'review_id',
        'product_id',
        'user_id',
        'rating',
        'komentar',
        'status_review',
        'replied',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->review_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
