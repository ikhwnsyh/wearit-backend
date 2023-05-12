<?php

use App\Http\Controllers\API\AlamatController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\DataTubuhController;
use App\Http\Controllers\API\IndexController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransaksiController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [UserController::class, 'regist']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
    Route::post('isi-alamat', [AlamatController::class, 'store']);
    Route::post('isi-datatubuh', [DataTubuhController::class, 'store']);

    Route::get('product/detail/{id}', [ProductController::class, 'detailProduct']);

    Route::get('profile/alamat', [UserController::class, 'getAlamat']);

    Route::post('cart/{id}', [CartController::class, 'store']);
    Route::get('cart', [CartController::class, 'cart']);

    // Route::get('pembayaran', [TransaksiController::class, 'pembayaran']);
    Route::get('beli-langsung', [TransaksiController::class, 'beli']);
    Route::middleware('admin')->group(function () {
        Route::post('product/tambah-product', [ProductController::class, 'store']);
    });
});
