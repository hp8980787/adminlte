<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Imports\OrdersImport;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function editable(Request $request)
    {
        $order = Order::query()->findOrFail($request->id);
        $order->update([
            $request->name => $request->value
        ]);
        return response()->json(['success' => true]);
    }

    public function link(Request $request)
    {
        $ids = $request->id;
        if (!$ids) {
            return response('error', 500);
        }
        $orders = Order::query()->whereIn('id', $ids)->get();
        $codes = [];
        $unFind = [];
        $exists = [];
        $link = [];
        foreach ($orders as $order) {

            $codes = array_map(function ($order) {
                $array = explode('|', $order);
                return [
                    'pcode' => $array[0],
                    'quantity' => $array[1]
                ];
            }, array_filter(explode(',', $order->product_code)));
            foreach ($codes as $code) {
                $product = DB::table('products')->where('pcode', 'like', "%$code[pcode]%")
                    ->orWhere('pcodes', 'like', "%$code[pcode]%")
                    ->first();

                if ($product) {
                    if ($res = DB::table('order_products')->where('product_id', $product->id)->where('order_id', $order->id)->first()) {
                        $exists[] = $order->id;
                    } else {
                        $link[] = $order->id;
                        $order->products()->attach($product->id, ['quantity' => $code['quantity']]);
                    }
                } else {
                    $unFind[] = $order->id;
                }
            }

        }

        Order::query()->whereIn('id', array_merge($exists, $link))->update([
            'link_status' => 1
        ]);
        //判断是否能发货
        $orders = Order::query()->with('products')->whereIn('id', array_merge($exists, $link))->get();

        foreach ($orders as $order) {
            $products = $order->products;
            foreach ($products as $product) {
                if ($product->stock >= $product->pivot->quantity) {
                    $order->is_shipping = 1;
                    $order->save();
                }

            }

        }
        Order::query()->whereIn('id', $unFind)->update([
            'link_status' => -1
        ]);
        return response()->json([
            'unFind' => $unFind,
            'exists' => $exists,
            'link' => $link
        ]);
    }

    /*
     * 订单发货
     *
     *
     */
    public function shipping(Request $request)
    {
        if (is_array($request->id)) {

        } else {
            $orders = Order::query()->with('products')->findOrFail($request->id);
        }
        $products = $orders->products;

    }

}
