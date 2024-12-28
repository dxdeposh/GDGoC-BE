<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $primaryKey = 'recommendation_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'recommendation_id',
        'user_id',
        'product_id',
        'score',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->recommendation_id = (string) \Illuminate\Support\Str::uuid();
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
