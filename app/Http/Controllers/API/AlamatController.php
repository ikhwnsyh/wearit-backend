<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    public function provinsi()
    {
        $finalNames = [];
        $data = Province::where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);
        foreach ($data as $newData) {
            $small = strtolower($newData->name);
            $new = ucwords($small);
            $finalNames[] = $new;
        }
        return response()->json($finalNames);
    }

    public function kabupaten($id)
    {
        $finalNames = [];
        $data = Regency::where('province_id', $id)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);
        foreach ($data as $newData) {
            $small = strtolower($newData->name);
            $new = ucwords($small);
            $finalNames[] = $new;
        }
        return response()->json($finalNames);
    }

    public function kecamatan($id)
    {
        $finalNames = [];
        $data = District::where('regency_id', $id)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);
        foreach ($data as $newData) {
            $small = strtolower($newData->name);
            $new = ucwords($small);
            $finalNames[] = $new;
        }
        return response()->json($finalNames);
    }

    public function kelurahan($id)
    {
        $finalNames = [];
        $data = Village::where('district_id', $id)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);
        foreach ($data as $newData) {
            $small = strtolower($newData->name);
            $new = ucwords($small);
            $finalNames[] = $new;
        }
        return response()->json($finalNames);
    }
}
