<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
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
            if ($search = $request->search) {
                $query->where('trans_id', 'like', "%$search%")
                    ->orWhere('order_number', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('country', 'like', "%$search%")
                    ->orWhere('state', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('postal', 'like', "%$search%")
                    ->orWhere('street1', 'like', "%$search%")
                    ->orWhere('street2', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('product_code', 'like', "%$search%");
            }
            if ($request->sortName) {
                $query->orderBy($request->sortName, $request->sortOrder);
            }
            $perPage = $request->perPage ?? 20;

            $data = $query->orderBy('id', 'desc')->paginate($perPage);

            return response()->json(new OrderCollection($data));
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
