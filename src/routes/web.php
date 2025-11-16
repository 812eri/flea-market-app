<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\HomeController;

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

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['verified'])->group(function () {
        Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
        Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('item.like.store');
Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('item.like.destroy');
Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('item.comment');

Route::get('/purchase/{item_id}', [ItemController::class, 'purchaseShow'])->name('purchase.show');
Route::post('/item/{item_id}/purchase', [ItemController::class, 'purchase'])->name('item.purchase');

Route::get('purchase/stripe-redirect', function (Request $request) {
    return redirect()->route('purchase.complete', $request->query());
})->name('/purchase.stripe.redirect');
Route::get('/purchase/complete', [ItemController::class, 'purchaseComplete'])->name('purchase.complete');

Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('address.edit');
Route::post('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('purchase.address.update');

Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.show');
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');

});
});
