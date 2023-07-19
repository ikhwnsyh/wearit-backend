<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'size_id'      => 'required',
            'quantity' => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user_id = Auth::id();
        $product = Product::find($request->product_id);
        $quantity =  Size::find($request->size_id);
        if ($quantity) {
            if ($quantity->stock == 0) {
                return response()->json([
                    'success' => false,
                    'pesan' => "Stock barang 0. Gagal menambahkan barang ke cart!",
                ], 200);
            }
            if ($product) {
                if (Size::where('id', $quantity->id)->where('product_id', $product->id)->first()) {
                    $cart = Cart::where('product_id', $product->id)
                        ->where('user_id', $user_id)->where('size_id', $request->size_id)
                        ->first();
                    if ($cart) {
                        if ($cart->quantity < $quantity->stock) {
                            $cart->update(['quantity' => $cart->quantity + 1]);
                        } else {
                            return response()->json([
                                'success' => false,
                                'pesan' => "Jumlah barang pada keranjang anda sudah melebihi stock yang kami punya!",
                            ], 200);
                        }
                    } else {
                        Cart::create([
                            'user_id' => $user_id,
                            'product_id' => $product->id,
                            'quantity' => 1,
                            'size_id' => $request->size_id
                        ]);
                    }
                    return response()->json([
                        'success' => true,
                        'pesan' => "succsess add to cart!",
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'pesan' => "Size tidak ditemukan!",
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'pesan' => "Product tidak ditemukan!",
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'pesan' => "Size tidak ditemukan!",
            ], 404);
        }
    }

    public function cart()
    {
        $dataCart = Cart::with('dataProduct', 'productSize')
            ->where('user_id', Auth::user()->id)->get();
        foreach ($dataCart as  $checkStock) {
            $stock = Size::where('id', $checkStock->size_id)->first();
            if ($checkStock->quantity >  $stock->stock); {
                $checkStock->update([
                    'quantity' => $stock->stock,
                ]);
            }
        }
        if ($dataCart->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data_cart'    => $dataCart,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message'    => "Keranjang kosong. Anda belum memasukkan barang ke keranjang!",
            'data_cart' => null
        ], 200);
    }

    public function delete(Cart $cart)
    {
        $cart::where('user_id', Auth::user()->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'data cart anda berhasil dihapus!',
        ], 200);
    }
}
