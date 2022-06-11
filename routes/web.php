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

Route::name('admin.')->prefix('admin')->group(function (){
    Route::get('dashboard',[\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('dashboard');
    Route::resource('products',\App\Http\Controllers\Admin\ProductsController::class)->parameter('products','id');
    Route::get('generate/sku',[\App\Http\Controllers\Admin\HelpersController::class,'sku'])->name('sku');
    Route::resource('purchase',\App\Http\Controllers\Admin\PurchaseController::class)->parameter('purchase','id');
});
