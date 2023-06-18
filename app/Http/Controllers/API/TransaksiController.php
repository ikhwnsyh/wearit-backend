<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Detail;
use App\Models\Ekspedisi;
use App\Models\Size;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function beli()
    {
        $ekspedisi = Ekspedisi::all();
        $userAddress = Auth::user()->alamat;
        if ($ekspedisi and $userAddress) {
            return response()->json([
                'success' => true,
                'ekspedisi' => $ekspedisi,
                'userAddress' => $userAddress,
            ], 200);
        }
    }

    public function bayar(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'size_id'      => 'required',
            ],
            [
                'size_id.required' => 'Dipilih dulu kak sizenya',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $product_id = $request->product_id;
        $size_id = $request->size_id;
        $quantity = $request->quantity;
        foreach ($product_id as $index => $product) {
            if (Size::where('id', $size_id[$index])->first()->stock >= $quantity[$index]) {
                $transaksi = Transaksi::create([
                    'user_id' => Auth::user()->id,
                    'ekspedisi_id' => $request->ekspedisi_id,
                    'status_id' => 1,
                    'paid' => false,
                ]);
                if ($transaksi) {
                    $detailTransaksi = Detail::create([
                        'transaksi_id' => $transaksi->id,
                        'product_id' => $product,
                        'alamat_id' => $request->alamat_id,
                        'size_id' => $size_id[$index],
                        'price' => $request->price,
                        'final_price' => $request->final_price,
                        'transaction_date' => Carbon::now(),
                        'quantity' => $quantity[$index],
                    ]);
                    $updateStock = Size::where('id', $size_id[$index])->first();
                    $newStock = $updateStock->stock - $quantity[$index];
                    $updateStock->update([
                        'stock' => $newStock,
                    ]);
                    if ($request->has('cart_id')) {
                        $cart = Cart::where('user_id', Auth::id())->delete();
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'kuantitas pada produk melebihi stok tersedia',
                ], 200);
            }
            return response()->json([
                'success' => true,
                'data' => $transaksi,
            ], 200);
        }
        return response()->json([
            'success' => false,
        ], 422);
    }
}
