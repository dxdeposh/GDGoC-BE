<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'activity_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'activity_id',
        'user_id',
        'action',
        'detail',
    ];

    protected $casts = [
        'detail' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->activity_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
