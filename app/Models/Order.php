<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'trans_id', 'order_number', 'total', 'total_usd', 'currency', 'name', 'phone', 'email', 'postal',
        'country', 'state', 'city', 'street1', 'street2', 'ip', 'description', 'product_code', 'status'
    ];

    protected $casts = [
        'created_at' => 'string:Y-m-d H:i:s'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class,'order_products','order_id','product_id')->withTimestamps()->withPivot('quantity');
    }


}
