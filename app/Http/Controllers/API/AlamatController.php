<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    public function provinsi()
    {
        $finalNames = [];
        $data = Province::where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);
        foreach ($data as $newData) {
            $small = strtolower($newData->name);
            $new = ucwords($small);
            $finalNames[] = [
                'id' => $newData->id,
                'name' => $new,
            ];
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
            $finalNames[] = [
                'id' => $newData->id,
                'name' => $new,
            ];
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
            $finalNames[] = [
                'id' => $newData->id,
                'name' => $new,
            ];
        }
        return response()->json($finalNames);
    }

    public function kelurahan($id)
    {
        $data = Village::where('district_id', $id)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);
        foreach ($data as $newData) {
            $small = strtolower($newData->name);
            $new = ucwords($small);
            $finalNames[] = [
                'id' => $newData->id,
                'name' => $new,
            ];
        }
        return response()->json($finalNames);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alamat'      => 'required',
            'province_id' => 'required',
            'regency_id'      => 'required',
            'district_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $alamat = Alamat::create([
            'user_id' => Auth::user()->id,
            'alamat' => $request->alamat,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahakan!',
        ], 200);
    }
}
