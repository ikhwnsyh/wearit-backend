<?php

use App\Http\Controllers\API\AlamatController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\DataTubuhController;
use App\Http\Controllers\API\IndexController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\TransaksiController;
use App\Http\Controllers\API\UserController;
use App\Models\Product;
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
Route::get('dashboard/product', [ProductController::class, 'allProduct']);

Route::middleware('auth:api')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
    Route::get('product/{id}', [IndexController::class, 'detailProduct']);
    Route::get('cart', [CartController::class, 'cart']);
    Route::post('cart/{id}', [CartController::class, 'store']);

    Route::post('isi-alamat', [AlamatController::class, 'store']);
    Route::post('isi-datatubuh', [DataTubuhController::class, 'store']);

    Route::get('profile', [ProfileController::class, 'profile']);
    Route::get('profile/edit', [ProfileController::class, 'editProfile']);
    Route::put('update-profile', [ProfileController::class, 'updateProfile']);

    Route::get('profile/alamat', [ProfileController::class, 'showAddress']);
    Route::get('profile/edit-alamat/{id}', [ProfileController::class, 'editAddress']);
    Route::put('update-alamat/{id}', [ProfileController::class, 'updateAddress']);
    Route::delete('profile/hapus-alamat/{id}', [ProfileController::class, 'deleteAddress']);

    Route::get('profile/data-tubuh', [ProfileController::class, 'showDataTubuh']);
    Route::put('update-data-tubuh', [ProfileController::class, 'updateDataTubuh']);

    // Route::get('pembayaran', [TransaksiController::class, 'pembayaran']);
    Route::post('bayar', [TransaksiController::class, 'bayar']);


    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashoard']);

        Route::post('dashboard/tambah-product', [ProductController::class, 'store']);
        Route::get('dasboard/edit-product/{id}', [ProductController::class, 'editProduct']);
        Route::put('update-product/{id}', [ProductController::class, 'updateProduct']);

        Route::get('dashboard/transaksi', [TransaksiController::class, 'allTransaction']);
        Route::get('dashboard/transaksi/{id}', [TransaksiController::class, 'transactionWaitToApprove']);
        Route::get('dashboard/transaksi/{id}', [TransaksiController::class, 'transactionWaitToSend']);

        Route::put('approved/{id}', [TransaksiController::class, 'approveTransaction']);
        Route::put('rejected/{id}', [TransaksiController::class, 'rejectTransaction']);
        Route::put('ongoing/{id}', [TransaksiController::class, 'onGoing']);
    });
});
