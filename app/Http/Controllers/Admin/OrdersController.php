<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderShipped;
use App\Events\UpdateOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Imports\OrdersImport;
use App\Models\Logistics;
use App\Models\Order;
use App\Models\Storehouse;
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

        $logistics = Logistics::query()->pluck('name', 'id');
        return view('admin.orders.index', compact('logistics'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls '
        ]);
        Excel::import(new OrdersImport(), $request->file('file'));
        return back()->with('success', '????????????');
    }

    /**
     * @note pcode??????
     *
     */
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
            return response('pcode ????????????', 500);
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

        //?????????????????????
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
     * ????????????
     *
     *
     */
    public function shipping(Request $request)
    {
        $storehouseId = $request->storehouse_id;

        if (is_array($request->id)) {

        } else {
            $order = Order::query()->with('products')->findOrFail($request->id);


            try {
                DB::beginTransaction();
                $products = $order->products;
                //???????????? ?????????????????? ??????????????????????????????
                foreach ($products as $product) {
                    $quantity = $product->pivot->quantity;
                    $warehouse = $product->warehouse()->where('storehouse_id', $storehouseId)->first();
                    //????????????????????????
                    if ($warehouse->pivot->stock >= $quantity) {
                        $stock = $warehouse->pivot->stock - $quantity;
                        $product->warehouse()->syncWithoutDetaching([$warehouse->id => ['stock' => $stock]]);
                    } else {
                        return back()->with('error', '??????????????????');
                        throw new \Exception('??????????????????', 500);
                    }
                    $product->stock = $product->stock - $quantity;
                    $product->sales = $product->sales + $quantity;
                    $product->save();
                }
                $order->status = Order::ORDER_STATUS_DELIVERED;
                $order->ship_no = $request->ship_no;
                $order->logistics_company = $request->logistics_company;
                $order->logistics_price = $request->logistics_price;
                $order->save();
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                throw new \Exception($exception->getMessage());
            }
        }
        OrderShipped::dispatch($order);
        return redirect()->route('orders.index')->with('success', '????????????');
    }

    /*
     *
     * ????????????????????????
     * ?????????????????????????????????????????????????????????????????????????????????????????????????????????
     */
    public function warehouse(Request $request)
    {
        $order = Order::query()->with('products')->findOrFail($request->id);
        $products = $order->products;
        $data = [];
        $warehouse = [];
        //??????????????????????????????????????????
        foreach ($products as $product) {
            //?????????????????? ?????????stock????????????????????????
            $results = DB::table('product_storehouse')->where('product_id', $product->id)
                ->where('stock', '>=', $product->pivot->quantity)->get();
            //?????????????????????????????????????????????
            if (sizeof($results) > 0) {
                $storehouse = $results->pluck('storehouse_id')->toArray();
                $data[] = $storehouse;
            }

        }
        if (sizeof($data) < 1) return response('????????????????????????', 500);

        //??????????????????????????????id????????????????????????????????????????????????
        $intersect = array_intersect(...$data);

        if (sizeof($intersect) < 1) return response('????????????????????????', 500);

        $storehouses = Storehouse::query()->whereIn('id', $intersect)->get();

        return response()->json($storehouses);
    }

    public function detail(Request $request)
    {
        $orders = Order::query()->with('products')->findOrFail($request->id);

        return response()->json($orders->products);
    }

}
