<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
        $user =$request->user();
        if ($user->hasRole(['admin','superAdmin'])){
            return $next($request);
        }
        $action =$request->route()->action;
        $name = $action['as'];
//        dd($name=='dashboard');
        $role = Role::query()->findOrFail(2);
//        dd($role->permissions);
//        dd($user->hasPermissionTo($name));

        if ($user->hasPermissionTo($name)){
            return $next($request);
        }
        return $next($request);
        abort(403);
    }
}
