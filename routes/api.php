<?php

use App\Http\Controllers\API\AlamatController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\EkspedisiController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RegistrasiController;
use App\Http\Controllers\API\TransaksiController;
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



Route::post('/login', LoginController::class);
Route::post('/register', RegistrasiController::class);

Route::get('getProvinsi', [AlamatController::class, 'provinsi']);
Route::get('getKabupaten/{id}', [AlamatController::class, 'kabupaten']);
Route::get('getKecamatan/{id}', [AlamatController::class, 'kecamatan']);
Route::get('getKelurahan/{id}', [AlamatController::class, 'kelurahan']);

Route::get('/index', [ProductController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::middleware('konsumen')->group(function () {
        Route::get('product/{slug}', [ProductController::class, 'detailProduct']);

        Route::get('cart', [CartController::class, 'cart']);
        Route::post('cart', [CartController::class, 'store']);
        Route::delete('hapus-cart', [CartController::class, 'delete']);

        Route::get('profile', [ProfileController::class, 'profile']);
        Route::get('profile/edit', [ProfileController::class, 'profile']);
        Route::put('update-profile', [ProfileController::class, 'updateProfile']);

        Route::get('profile/alamat', [ProfileController::class, 'showddress']);
        Route::post('profile/tambah-alamat', [AlamatController::class, 'store']);
        Route::get('profile/edit-alamat/{id}', [ProfileController::class, 'editAddress']);
        Route::put('update-alamat/{id}', [ProfileController::class, 'updateAddress']);
        Route::delete('profile/hapus-alamat/{id}', [ProfileController::class, 'deleteAddress']);

        Route::get('profile/body', [ProfileController::class, 'showDataTubuh']); //ralat jadi body
        Route::put('update-data-tubuh', [ProfileController::class, 'updateDataTubuh']);

        Route::get('profile/data-transaction', [ProfileController::class, 'dataTransaction']);
        Route::get('profile/data-transaction/{id}', [ProfileController::class, 'invoice']);

        Route::get('profile/menunggu-pembayaran', [ProfileController::class, 'listToWait']);
        Route::get('profile/menunggu-pembayaran/{id}', [ProfileController::class, 'uploadBukti']);
        Route::post('store-bukti', [ProfileController::class, 'storeBukti']);

        Route::put('selesai/{id}', [ProfileController::class, 'finished']);

        Route::get('beli-langsung', [TransaksiController::class, 'beli']);
        Route::post('bayar', [TransaksiController::class, 'bayar']);
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard']);
        Route::get('dashboard/product', [DashboardController::class, 'getAllProduct']);
        Route::post('dashboard/tambah-product', [ProductController::class, 'store']);
        Route::get('dasboard/edit-product/{slug}', [ProductController::class, 'editProduct']);
        Route::put('update-product/{id}', [ProductController::class, 'updateProduct']);
        Route::delete('hapus-product/{slug}', [ProductController::class, 'delete']);

        Route::get('dashboard/transaksi', [DashboardController::class, 'allTransaction']);
        Route::get('dashboard/transaksi/{slug}', [DashboardController::class, 'filterTransaction']);

        Route::put('approved/{id}', [DashboardController::class, 'approveTransaction']);
        Route::put('rejected/{id}', [DashboardController::class, 'rejectTransaction']);
        Route::put('sent/{id}', [DashboardController::class, 'sent']);

        Route::put('read', [TransaksiController::class, 'read']);
    });
});
