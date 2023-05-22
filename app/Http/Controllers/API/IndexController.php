<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $produk = Product::get();
        if ($produk) {
            return response()->json([
                'success' => true,
                'data_produk'    => $produk,
            ], 201);
        }
        return response()->json([
            'success' => true,
            'message'    => "Belum ada produk!",
        ], 204);
    }

    public function detailProduct($slug)
    {
        $detailProduct = Product::where('slug', $slug)->first();
        // session()->put('product', $detailProduct);
        return response()->json([
            'success' => true,
            'detailProduct'    => $detailProduct,
        ], 201);
    }
}
