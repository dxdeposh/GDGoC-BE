<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'log_id',
        'product_id',
        'action',
        'user_id',
        'detail',
    ];

    protected $casts = [
        'detail' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->log_id = (string) \Illuminate\Support\Str::uuid();
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
