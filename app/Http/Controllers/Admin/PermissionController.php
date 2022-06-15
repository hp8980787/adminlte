<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Permission::query();
            if ($request->search) {
                $search = $request->search;
                $query->where('name', 'like', "%$search%");
            }
            $perPage = $request->perPage ?: 20;
            $permissions = $query->paginate($perPage);
            return response()->json(responseTable($permissions->toArray(), $permissions, 'permissions.edit', 'permissions.destroy'));
        }

        return view('admin.permissions.index');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Permission::query()->create([
            'name' => $request->name
        ]);
        return redirect(adminRoute('permissions.index'))->with('toast_success', '成功！');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::query()->findOrFail($id);

        return view('admin.permissions.edit', compact('permission'));
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
        $data = $request->only('name', 'web_guard');
        Permission::query()->where('id', $id)->update($data);
        return redirect(adminRoute('permissions.index'))->with('toast_success', '成功!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permission::query()->where('id', $id)->delete();
        return response('success');
    }

    public function all()
    {
        $permissions = Permission::query()->get()->pluck('name', 'id');
        return response()->json($permissions);
    }
}
