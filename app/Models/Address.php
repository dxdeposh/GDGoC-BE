<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $primaryKey = 'address_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'address_id',
        'user_id',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'negara',
        'lokasi_geolokasi_latitude',
        'lokasi_geolokasi_longitude',
        'tipe_alamat',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->address_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
