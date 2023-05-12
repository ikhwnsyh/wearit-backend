<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // public function beli()
    // {
    //     $listProduct = session()->get('data');
    //     dd(session('data'));
    //     return response()->json([
    //         'success' => true,
    //         'dataTransaksi'    => $listProduct,
    //     ], 201);
    // }

    public function beli(Request $request)
    {
        if ($request->id) {
            $product = Product::where('id', $request->id)->first();
            if ($product) {
                // session()->put('data', $product->id);
                // dd(session('data'));

                return response()->json([
                    'success' => true,
                    'data' => $product,
                ], 200);
            }
            return response()->json([
                'success' => false,
            ], 404);
        }
        return response()->json([
            'success' => false,
        ], 422);
    }
}
