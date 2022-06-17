<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::name(config('admin.route.name'))->prefix(config('admin.route.prefix'))->middleware(['auth','admin'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('products/pagination', [\App\Http\Controllers\Admin\ProductsController::class, 'page'])->name('products.pagination');
    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class)->parameter('products', 'id');
    Route::get('generate/sku', [\App\Http\Controllers\Admin\HelpersController::class, 'sku'])->name('sku');
    Route::resource('purchase', \App\Http\Controllers\Admin\PurchaseController::class)->parameter('purchase', 'id');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->parameter('users', 'id');
    Route::put('users-assign-roles', [\App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('users.assign-roles');
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->parameter('roles', 'id');
    Route::get('roles-all', [\App\Http\Controllers\Admin\RoleController::class, 'all'])->name('roles.all');
    Route::put('assign-permission', [\App\Http\Controllers\Admin\RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->parameter('permissions', 'id');
    Route::get('permissions-all', [\App\Http\Controllers\Admin\PermissionController::class, 'all'])->name('permissions.all');
    Route::get('assign-role', [\App\Http\Controllers\Admin\UserController::class, 'assignRole'])->name('user.assign-role');
    Route::get('notifications',[\App\Http\Controllers\Admin\NotificationController::class,'index'])->name('notifications.index');

});
