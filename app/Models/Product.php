<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_name', 'category_id', 'brand_id', 'price', 'stock', 'image_url'];

    public function category () {
        return $this->belongsTo(Category::class);

    }

    public function brand () {
        return $this->belongsTo(Brand::class);
    }

}
