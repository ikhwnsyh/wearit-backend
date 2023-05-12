<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_product'      => 'required',
            'deskripsi'      => 'required',
            'harga'      => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $product = Product::create([
            'nama_product'      => $request->nama_product,
            'deskripsi'      => $request->deskripsi,
            'harga'      => $request->harga,
        ]);

        //return response JSON user is created
        if ($product) {
            return response()->json([
                'success' => true,
                'product'    => $product,
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }

    public function detailProduct($id)
    {
        $detailProduct = Product::where('id', $id)->first();
        // session()->put('product', $detailProduct);
        return response()->json([
            'success' => true,
            'detailProduct'    => $detailProduct,
        ], 201);
    }
}
