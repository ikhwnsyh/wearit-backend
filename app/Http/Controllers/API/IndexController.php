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
                'prod$produk'    => $produk,
            ], 201);
        }
        return response()->json([
            'success' => true,
            'message'    => "Belum ada produk!",
        ], 204);
    }
}
