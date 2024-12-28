<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'sub_category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sub_category_id',
        'category_id',
        'nama_sub_kategori',
        'deskripsi',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->sub_category_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'sub_category_id');
    }
}
