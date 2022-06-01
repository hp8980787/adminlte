<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'admin_products';
    protected $fillable = [
        'name', 'sku', 'category', 'brand', 'dl', 'dy', 'size', 'bzq', 'price_eu', 'price_us', 'price_uk',
        'price_jp', 'replace', 'stock', 'description', 'cover_img', 'imgs'
    ];
}
