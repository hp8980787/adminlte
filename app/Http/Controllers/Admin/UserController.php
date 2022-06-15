<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(UserRequest $request)
    {

        if ($request->ajax()) {
            $query = User::query()->with('roles');
            if ($request->search) {
                $search = $request->search;
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            }
            $perPage = $request->perPage ?: 20;
            $users = $query->paginate($perPage);

            return response()->json(new UserCollection($users));

        }
        return view('admin.users.index');
    }

    public function create()
    {

    }

    public function store()
    {
    }

    public function destroy()
    {

    }

    public function assignRoles(Request $request)
    {

        $id = $request->user_id;
        $roles = $request->roles;
        $user = User::query()->findOrFail($id);
        $user->syncRoles($roles);
        return response('success');
    }

}
