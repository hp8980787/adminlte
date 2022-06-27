<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $imgPath='https://www.batteriexpert.com/img/';
    protected $fillable = [
        'name', 'sku', 'category', 'brand', 'dl', 'dy', 'size', 'bzq', 'price_eu', 'price_us', 'price_uk',
        'price_jp', 'replace', 'stock', 'description', 'cover_img', 'imgs'
    ];
    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s'
    ];

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function getCoverImgAttribute($value)
    {
        return $this->imgPath . $value.'.jpg';
    }

}
