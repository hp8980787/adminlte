<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PurchaseRequest;
use App\Http\Resources\PurchaseCollnection;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Notifications\CreatePurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->perPage ?: 20;
            $query = Purchase::query()->with(['items']);
            if ($request->search) {
                $search = $request->search;
                $query->where('remark', 'like', "%$search%");
            }
            $data = $query->paginate($perPage);
            return response()->json(new PurchaseCollnection($data));
        }

        return view('admin.purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.purchase.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseRequest $request)
    {
        $user = Auth::user();
        $purchaseData = [];
        $purchaseData['user_id'] = $user->id;
        $purchaseData['deadline_at'] = $request->get('deadline_at');
        $purchaseData['remark'] = $request->get('remark');
        $purchaseData['supplier_id'] = $request->get('supplier_id');
        $purchaseData['title'] = $request->get('title');
        $product_id = $request->product_id;
        $price = $request->price;
        $quantity = $request->quantity;
        $explain = $request->explain;
        $storehouse = $request->storehouse_id;
        try {
            DB::beginTransaction();
            $purchase = Purchase::query()->create($purchaseData);
            $item = [];
            foreach ($product_id as $k => $v) {
                $item['storehouse_id'] = $storehouse[$k];
                $item['purchase_id'] = $purchase->id;
                $item['product_id'] = $v;
                $item['quantity'] = $quantity[$k];
                $item['price'] = $price[$k];
                $item['explain'] = $explain[$k];
                $item['amount'] = bcmul($item['quantity'], $item['price'], 2);
                PurchaseItem::query()->create($item);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('errors', $exception->getMessage());
        }
        $user->notify(new CreatePurchase($purchase));
        return redirect()->route('purchase.index')->with('success', '添加成功!');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Purchase::query()->where('id', $id)->delete();
        return response('success');
    }

    public function complete(Request $request)
    {
        $purchase = Purchase::query()->with('items')->findOrFail($request->id);
        if ($purchase->status != 1) {
            return response('发生错误', 500);
        }
        if ($purchase->status==2){
            return response('发生错误', 500);
        }
        if (sizeof($purchase->items) < 1) {
            return response('发生错误', 500);
        }
        foreach ($purchase->items as $item) {
            try {
                DB::beginTransaction();
                $storehouse = DB::table('product_storehouse')
                    ->where('product_id', $item->product_id)
                    ->where('storehouse_id', $item->storehouse_id)->first();
                if ($storehouse) {
                    DB::table('product_storehouse')->where('id', $storehouse->id)->update([
                        'stock' => bcadd($storehouse->stock, $item->quantity, 0),
                    ]);
                } else {
                    DB::table('product_storehouse')->insert([
                        'product_id' => $item->product_id,
                        'storehouse_id' => $item->storehouse_id,
                        'stock' => $item->quantity,
                    ]);
                }
                $product = Product::query()->findOrFail($item->product_id);
                $product->increment('stock', $item->quantity);
                $product->save();
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                dd($exception->getMessage());
            }
        }
        $purchase->status = 2;
        $purchase->save();
        return response('成功');
    }
}
