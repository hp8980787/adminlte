<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PurchaseRequest;
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
            $query = Purchase::query()->with('items');
            if ($request->search) {
                $search = $request->search;
                $query->where('remark', 'like', "%$search%");
            }
            $data = $query->paginate($perPage);
            return response()->json($data);
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

        $product_id = $request->product_id;
        $price = $request->price;
        $quantity = $request->quantity;
        $explain = $request->explain;
        try {
            DB::beginTransaction();
            $purchase = Purchase::query()->create($purchaseData);
            $item = [];
            foreach ($product_id as $k => $v) {
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
        //
    }
}
