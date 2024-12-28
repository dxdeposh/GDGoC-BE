<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'transaction_id',
        'order_id',
        'metode_pembayaran',
        'status_transaksi',
        'detail_pembayaran',
        'amount',
        'currency',
        'payment_gateway_id',
    ];

    protected $casts = [
        'detail_pembayaran' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->transaction_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id', 'payment_gateway_id');
    }
}
