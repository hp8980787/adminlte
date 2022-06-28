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
    Route::post('purchase-complete',[\App\Http\Controllers\Admin\PurchaseController::class,'complete'])->name('purchase.complete');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->parameter('users', 'id');
    Route::put('users-assign-roles', [\App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('users.assign-roles');
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->parameter('roles', 'id');
    Route::get('roles-all', [\App\Http\Controllers\Admin\RoleController::class, 'all'])->name('roles.all');
    Route::put('assign-permission', [\App\Http\Controllers\Admin\RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->parameter('permissions', 'id');
    Route::get('permissions-all', [\App\Http\Controllers\Admin\PermissionController::class, 'all'])->name('permissions.all');
    Route::get('assign-role', [\App\Http\Controllers\Admin\UserController::class, 'assignRole'])->name('user.assign-role');
    Route::get('notifications',[\App\Http\Controllers\Admin\NotificationController::class,'index'])->name('notifications.index');
    Route::resource('supplier',\App\Http\Controllers\Admin\SupplierController::class)->parameter('supplier','id');
    Route::resource('storehouse',\App\Http\Controllers\Admin\StorehouseController::class)->parameter('storehouse','id')->except('show');
    Route::resource('orders',\App\Http\Controllers\Admin\OrdersController::class)->parameter('orders','id')->except('show');
    Route::get('orders-detail',[\App\Http\Controllers\Admin\OrdersController::class,'detail'])->name('orders.detail');
    Route::post('orders-import',[\App\Http\Controllers\Admin\OrdersController::class,'import'])->name('orders.import');
    Route::put('orders-editable',[\App\Http\Controllers\Admin\OrdersController::class,'editable'])->name('orders.editable');
    Route::post('orders-link',[\App\Http\Controllers\Admin\OrdersController::class,'link'])->name('orders.link');
    Route::post('orders-shipping',[\App\Http\Controllers\Admin\OrdersController::class,'shipping'])->name('orders.shipping');
    Route::get('orders-warehouse',[\App\Http\Controllers\Admin\OrdersController::class,'warehouse'])->name('orders.warehouse');
    Route::get('rate/select',[\App\Http\Controllers\Admin\RateController::class,'select'])->name('rate.select');

});
