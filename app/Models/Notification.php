<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'notification_id',
        'user_id',
        'type',
        'message',
        'is_read',
        'tanggal',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->notification_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
