<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
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
        $userAddress = Alamat::where('user_id', Auth::id())->with('province', 'kabupaten', 'kecamatan')->get();
        foreach ($userAddress as $address) {
            $address->province->name = ucwords(strtolower($address->province->name));
            $address->kabupaten->name = ucwords(strtolower($address->kabupaten->name));
            $address->kecamatan->name = ucwords(strtolower($address->kecamatan->name));
        }
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
                    'final_price' => $request->final_price,
                    'status_id' => 1,
                    'paid' => false,
                    'alamat_id' => $request->alamat_id,
                    'transaction_date' => Carbon::now(),
                ]);
                if ($transaksi) {

                    Detail::create([
                        'transaksi_id' => $transaksi->id,
                        'product_id' => $product,
                        'size_id' => $size_id[$index],
                        'price' => $request->price,

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
                'message' => 'transaksi berhasil dibuat!',
                'data' => $transaksi,
            ], 200);
        }
        return response()->json([
            'success' => false,
        ], 422);
    }
}
