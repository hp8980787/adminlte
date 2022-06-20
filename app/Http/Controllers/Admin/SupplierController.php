<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupplierRequest;
use App\Http\Resources\SupplierCollection;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SupplierRequest $request)
    {
        if ($request->ajax()) {
            if (!$request->page) {
                $data = Supplier::query()->get();
                return response()->json($data);
            } else {
                $query = Supplier::query();
                if ($search = $request->search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('web', 'like', "%$search%");
                }
                $perPage = $request->perPage ?? 20;
                $data = $query->paginate($perPage);
                return response()->json(new SupplierCollection($data));
            }
        }
        return view('admin.supplier.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        $data = $request->only('name', 'phone', 'email', 'web', 'address');

        Supplier::query()->create($data);

        return redirect()->route('supplier.index')->with('toast_success', '添加成功!');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::query()->findOrFail($id);
        return view('admin.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        $data = $request->only('name', 'phone', 'email', 'web', 'address');
        Supplier::query()->where('id', $id)->update($data);
        return redirect()->route('supplier.index')->with('success', '修改成功!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supplier::query()->where('id', $id)->delete();
        return response('success');

    }
}
