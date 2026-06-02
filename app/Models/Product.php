<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price',
        'sale_price', 'stock', 'image', 'images',
        'category_id', 'is_active',
    ];

    protected $casts = [
        'images'    => 'array',
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
        'sale_price'=> 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}