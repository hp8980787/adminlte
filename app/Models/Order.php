<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
        'trans_id','order_number','total','total_usd','currency','name','phone','email','postal',
        'country','state','city','street1','street2','status','description','product_code'
    ];
}
