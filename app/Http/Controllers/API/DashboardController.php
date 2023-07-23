<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Status;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalProduct = Product::count();
        $menungguPembayaran = Transaksi::where('status_id', 1)->count();
        $menungguApprove = Transaksi::where('status_id', 2)->count();
        $sedangDiproses = Transaksi::where('status_id', 3)->count();
        $pendapatan = Transaksi::where('paid', true)
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->flatten()
            ->pluck('final_price')
            ->sum();

        // Tampilkan atau gunakan nilai total final price
        return response()->json([
            'success' => true,
            'income' => $pendapatan,
            'diproses' => $sedangDiproses,
            'menunggu' => $menungguPembayaran,
            'approve' => $menungguApprove,
            'product' => $totalProduct
        ], 200);
    }

    public function getAllProduct()
    {
        $product = Product::with('productSize', 'image')->get();
        if ($product->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'product'    => $product,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'data produk kosong!',
            ], 200);
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
        $data = Transaksi::where('status_id', $statusId)->with(
            'userTransaction',
            'userAddress',
            'userAddress.province',
            'userAddress.kabupaten',
            'userAddress.kecamatan',
            'transactions.detailProduct',
            'transactions.detailSize',
        )->get();
        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Tidak ada data transaksi dengan status"
            ], 200);
        }
    }

    public function approveTransaction($id)
    {
        $updateStatus = Transaksi::find($id)->update([
            'status_id' => 3,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diapprove. Status transaksi menjadi sedang diproses',
            'updated' => $updateStatus,
        ], 200);
    }

    public function rejectTransaction($id)
    {
        $updateStatus = Transaksi::find($id)->update([
            'status_id' => 6
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Bukti tidak valid. Status pesanan menjadi dibatalkan!',
            'updated' => $updateStatus,
        ], 200);
    }
    public function sent($id)
    {
        $updateStatus =  Transaksi::where('id', $id)->update([
            'status_id' => 4
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Barang sedang dikirim menuju alamat!',
        ], 200);
    }
}
