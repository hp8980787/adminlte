<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utils\Admin\GeneratePermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
//        Role::create([
//            'name'=>'admin'
//        ]);
//        $user = Auth::user();
//        $user->assignRole('admin');
        $g = new GeneratePermissions();
//        $g->routePermissionsToDatabases();
//        $g->generateMenuToConfig();
        return view('admin.dashboard.index');
    }
}
