<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EkspedisiController extends Controller
{
    public function index()
    {
        $pickup = Transaksi::whereHas('ekspedisi', function ($query) {
            $query->where('id', Auth::id());
        })->get();
        dd($pickup);
    }
    public function pickUp($id)
    {
        $updateStatus = Transaksi::where('id', $id)->update([
            'status_id' => 5
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Paket sedang diantar oleh kurir menuju alamat!',
            'updated' => $updateStatus,
        ], 200);
    }

    public function finished($id)
    {
        $updateStatus = Transaksi::where('id', $id)->update([
            'status_id' => 6
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Paket telah sampai ditujuan!',
            'updated' => $updateStatus,
        ], 200);
    }
}
