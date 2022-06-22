<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

        }

        return view('admin.orders.index');
    }

    public function import(Request $request)
    {
       $request->validate([
            'file'=>'required|mimes:xlsx,csv,xls '
        ]);
       dd($request->file('file'));
    }

}
