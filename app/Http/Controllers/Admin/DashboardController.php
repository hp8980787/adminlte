<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utils\Admin\GeneratePermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {

        $g = new GeneratePermissions();
//        $g->routePermissionsToDatabases();

        return view('admin.dashboard.index');
    }
}
