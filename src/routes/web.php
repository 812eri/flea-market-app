<?php

use Illuminate\Support\Facades\Route;
use\App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AddressController;

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

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('item.comment')->middleware('auth');
Route::post('/purchase/address//{item_id}', [AddressController::class, 'update'])->name('purchase.address.update')->middleware('auth');
Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.show')->middleware('auth');
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
