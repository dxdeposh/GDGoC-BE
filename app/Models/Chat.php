<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $primaryKey = 'chat_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'chat_id',
        'order_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->chat_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }
}
