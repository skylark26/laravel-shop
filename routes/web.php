<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/category/{cat}', [ProductController::class, 'showCategory'])->name('showCategory');
Route::get('/category/{cat}/{product_id}', [ProductController::class, 'show'])->name('showProduct');
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cartIndex');
Route::post('/add-to-cart', [\App\Http\Controllers\CartController::class, 'addToCart'])->name('addToCart');
