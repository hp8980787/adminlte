<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function select(Request $request): JsonResponse
    {
        $currency = $request->currency;
        $price = $request->price;
        $rate = rate($currency);

        $usd = bcdiv($price, $rate, 2);

        return response()->json(['data' => $usd]);
    }
}
