<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\Product;
use App\Models\Status;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $payFalse = Transaksi::where('paid', false)->count();
        $paid = Transaksi::where('paid', true)->count();
        $totalPendapatan = Transaksi::where('paid', false)->with('transactions')->first();
        $totalProduct = Product::count();
        // $totalPendapatan = Detail::sum('final_price');
        dd($totalProduct);
    }

    public function getAllProduct()
    {
        $product = Product::with('productSize')->get();
        if ($product->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'product'    => $product,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'data produk kosong!',
            ], 201);
        }
    }

    public function allTransaction()
    {
        $allTransaction = Transaksi::with('transactions')->get();
        if ($allTransaction->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'allTransaction' => $allTransaction,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Data transaksi kosong. tidak ada transaksi"
            ], 200);
        }
    }

    public function filterTransaction($slug)
    {
        $status = Status::where('slug', $slug)->firstOrFail();
        $statusId = $status->id;
        $data = Transaksi::where('status_id', $statusId)->get();
        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Tidak ada data transaksi dengan status $slug"
            ], 200);
        }
    }

    public function approveTransaction($id)
    {
        $updateStatus = Transaksi::where('id', $id)->update([
            'status_id' => 3,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diapprove. Status berubah menjadi sedang diproses',
            'updated' => $updateStatus,
        ], 200);
    }

    public function rejectTransaction($id)
    {
        $updateStatus = Transaksi::where('id', $id)->update([
            'status_id' => 4
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Bukti tidak valid. Status pesanan menjadi dibatalkan!',
            'updated' => $updateStatus,
        ], 200);
    }

    public function onGoing($id)
    {
        $updateStatus =  Transaksi::where('id', $id)->update([
            'status_id' => 5
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Status updated! Produk sedang dikirim menuju alamat!',
            'updated' => $updateStatus,
        ], 200);
    }
}
