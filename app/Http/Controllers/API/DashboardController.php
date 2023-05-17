<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $dataTransaction = Transaksi::get();
        $totalPendapatan = $dataTransaction->map(function ($data) {
            return collect($data->toArray())
                ->only(['total_transaksi'])
                ->all();
        });
    }
}
