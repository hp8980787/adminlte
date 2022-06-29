<?php

namespace App\Http\Controllers\Admin;

use App\Events\UpdateOrder;
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
        $order = Order::query()->with('products')->findOrFail($request->id);
        $order->update([
            $request->name => trim($request->value)
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
        UpdateOrder::dispatch(array_unique(array_merge($exists, $link)));

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
        dd($products);

    }

    /*
     *
     * 订单选择仓库发货
     * 订单产品不可，拆分分仓库发送，要么所有产品都是本地仓库要么都是德国仓库
     */
    public function warehouse(Request $request)
    {
        $order = Order::query()->with('products')->findOrFail($request->id);
        $products = $order->products;
        $data = [];
        $judge = [];
        $warehouse = [];
        //订单产品不可，拆分分仓库发送
        foreach ($products as $product) {
            //符合发货条件 即仓库stock大于等于订单数量
            $results = DB::table('product_storehouse')->where('product_id', $product->id)
                ->where('stock', '>=', $product->pivot->quantity)->get();

            if (sizeof($results) > 0) {
                $storehouse = $results->pluck('id')->toArray();
                foreach ($storehouse as $value) {
                    $warehouse[]['id'] = $value;
                }

//                array_map(function ($v) use (&$warehouse) {
//                    return array_push($warehouse, $v);
//                }, $storehouse);
            }

        }

        dd($warehouse);

        return response();
    }

    public function detail(Request $request)
    {
        $orders = Order::query()->with('products')->findOrFail($request->id);
        return response()->json($orders->products);
    }

}
