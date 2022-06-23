<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\OrdersImport;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::query();
            if ($request->search) {

            }
            $perPage = $request->perPage ?? 20;

            $data = $query->paginate($perPage);

            return response()->json($data);
        }

        return view('admin.orders.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls '
        ]);
        Excel::import(new OrdersImport(), $request->file('file'));
        return back()->with('success', '导入成功');
    }

}
