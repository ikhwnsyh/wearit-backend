<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function store($id)
    {
        $user_id = Auth::id();
        $product = Product::find($id);

        if ($product) {
            $cart = Cart::where('product_id', $product->id)->where('user_id', $user_id)->first();
            if ($cart) {
                $cart->update(['quantity' => $cart->quantity + 1]);
            } else {
                Cart::create([
                    'user_id' => $user_id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
            return response()->json([
                'success' => true,
                'pesan' => "succsess add to cart!",
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'pesan' => "Product tidak ditemukan!",
            ], 404);
        }
    }

    public function cart()
    {
        $dataCart = Auth::user()->cart;
        if ($dataCart->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data cart'    => $dataCart,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message'    => "Keranjang kosong. Anda belum memasukkan barang ke keranjang!",
            'data_cart' => null
        ], 200);
    }
}
