<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\Ekspedisi;
use App\Models\Size;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $product_id = $request->product_id;
        $size_id = $request->size_id;
        $quantity = $request->quantity;
        if ($product_id and $size_id) {
            $transaksi = Transaksi::create([
                'user_id' => Auth::user()->id,
                'ekspedisi_id' => $request->ekspedisi_id,
                'status_id' => 1,
                'paid' => false,
            ]);
            if ($transaksi) {
                foreach ($product_id as $index => $product) {
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
                    $test = Size::where('id', $size_id[$index])->first();
                    if ($test) {
                        $newStock = $test->stock - $quantity[$index];
                        $test->update([
                            'stock' => $newStock,
                        ]);
                    }
                }
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
