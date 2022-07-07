<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistics extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url'];

    protected $casts = [
        'created_at'=>'string:Y-m-d H:i:s',
        'updated_at'=>'string:Y-m-d H:i:s',
    ];
}
