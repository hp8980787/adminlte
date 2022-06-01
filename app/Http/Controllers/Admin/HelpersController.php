<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelpersController extends Controller
{
    public function sku()
    {
        $now = now()->format('ymdhis');
       return response($now.substr(time(),-1,4).Str::random(5));
    }
}
