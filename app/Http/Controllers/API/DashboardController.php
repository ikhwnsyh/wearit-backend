<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $dataTransaction = Transaksi::get();
        $totalPendapatan = $dataTransaction->map(function ($data) {
            return collect($data->toArray())
                ->only(['total_transaksi'])
                ->all();
        });
    }

    public function allProduct()
    {
        $product = Product::with('productSize')->get();
        return response()->json([
            'success' => true,
            'product'    => $product,
        ], 201);
    }
}
