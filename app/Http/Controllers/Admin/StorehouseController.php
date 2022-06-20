<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Storehouse;
use Illuminate\Http\Request;

class StorehouseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->page) {
                $data = Storehouse::query()->get();
                foreach ($data as $v) {
                    $v->editUrl = adminRoute('storehouse.edit', $v->id);
                    $v->delUrl = adminRoute('storehouse.destroy', $v->id);
                }
                return response()->json($data);
            } else {
                $data = Storehouse::query()->get()->pluck('name', 'id');
                return response()->json($data);
            }
        }
        return view('admin.storehouse.index');
    }

    public function store(Request $request)
    {
        if (!$request->name) {
            return back()->with('error', 'name 必须');
        }
        Storehouse::query()->create([
            'name' => $request->name,
        ]);
        return redirect()->route('storehouse.index')->with('success', '添加成功!');
    }

    public function edit($id)
    {
        $storehouse = Storehouse::query()->findOrFail($id);
        return view('admin.storehouse.edit', compact('storehouse'));
    }

    public function update(Request $request, $id)
    {
        Storehouse::query()->where('id', $id)->update([
            'name' => $request->name
        ]);
        return redirect()->route('storehouse.index')->with('success', '修改成功!');
    }

    public function destroy($id)
    {
        Storehouse::query()->where('id',$id)->delete();
        return response('success');
    }
}
