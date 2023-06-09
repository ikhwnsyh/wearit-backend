<?php header('Access-Control-Allow-Origin: *');

use App\Http\Controllers\API\AlamatController;
use App\Http\Controllers\API\BodyController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\TransaksiController;
use App\Http\Controllers\API\UserController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

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
    Route::get('/', [ProductController::class, 'index']);
    Route::get('product/{slug}', [ProductController::class, 'detailProduct']);

    Route::get('cart', [CartController::class, 'cart']);
    Route::post('cart', [CartController::class, 'store']);
    Route::delete('hapus-cart', [CartController::class, 'delete']);

    Route::post('isi-alamat', [AlamatController::class, 'store']);
    Route::post('isi-datatubuh', [BodyController::class, 'store']);

    Route::get('profile', [ProfileController::class, 'profile']);
    Route::get('profile/edit', [ProfileController::class, 'profile']);
    Route::put('update-profile', [ProfileController::class, 'updateProfile']);

    Route::get('profile/alamat', [ProfileController::class, 'showAddress']);
    Route::get('profile/edit-alamat/{id}', [ProfileController::class, 'editAddress']);
    Route::put('update-alamat/{id}', [ProfileController::class, 'updateAddress']);
    Route::delete('profile/hapus-alamat/{id}', [ProfileController::class, 'deleteAddress']);

    Route::get('profile/data-tubuh', [ProfileController::class, 'showDataTubuh']); //ralat jadi body
    Route::put('update-data-tubuh', [ProfileController::class, 'updateDataTubuh']);

    Route::get('profile/data-transaction', [ProfileController::class, 'dataTransaction']);
    Route::get('profile/data-transaction/{id}', [ProfileController::class, 'invoice']);

    Route::get('profile/menunggu-pembayaran', [ProfileController::class, 'listToWait']);
    Route::get('profile/menunggu-pembayaran/{id}', [ProfileController::class, 'uploadBukti']);
    Route::post('store-bukti', [ProfileController::class, 'storeBukti']);
    Route::get('beli-langsung', [TransaksiController::class, 'beli']);
    Route::post('bayar', [TransaksiController::class, 'bayar']);

    Route::get('getProvinsi', [AlamatController::class, 'provinsi'])->name('provinsi.index');
    Route::get('getKabupaten/{id}', [AlamatController::class, 'kabupaten']);
    Route::get('getKecamatan/{id}', [AlamatController::class, 'kecamatan']);
    Route::get('getKelurahan/{id}', [AlamatController::class, 'keluarahan']);

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard']);
        Route::get('dashboard/product', [DashboardController::class, 'getAllProduct']);
        Route::post('dashboard/tambah-product', [ProductController::class, 'store']);
        Route::get('dasboard/edit-product/{slug}', [ProductController::class, 'editProduct']);
        Route::put('update-product/{id}', [ProductController::class, 'updateProduct']);

        Route::get('dashboard/transaksi', [DashboardController::class, 'allTransaction']);
        Route::get('dashboard/transaksi/{slug}', [DashboardController::class, 'filterTransaction']);

        Route::put('approved/{id}', [DashboardController::class, 'approveTransaction']);
        Route::put('rejected/{id}', [DashboardController::class, 'rejectTransaction']);
        Route::put('ongoing/{id}', [DashboardController::class, 'onGoing']);
    });
});
