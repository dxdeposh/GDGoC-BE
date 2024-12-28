<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_gateway_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_gateway_id',
        'nama_gateway',
        'api_key',
        'api_secret',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->payment_gateway_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_gateway_id', 'payment_gateway_id');
    }
}
