<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'nama_kategori',
        'deskripsi',
        'parent_category_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->category_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    // Relationships
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id', 'category_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'category_id');
    }
}
