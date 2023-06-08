<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $validator = Validator::make($request->all(), [
            'alamat'      => 'required|unique:alamats',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['user_id'] = $user_id;
        $alamat = Alamat::create($data);

        //return response JSON user is created
        if ($alamat) {
            return response()->json([
                'success' => true,
                'alamat'    => $alamat,
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }

    public function provinsi()
    {
        $data = Province::where('name', 'LIKE', '%' . request('q') . '%')->paginate(34);

        return response()->json($data);
    }

    public function kabupaten($id)
    {
        $data = Regency::where('province_id', $id)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);

        return response()->json($data);
    }
    public function kecamatan($id)
    {
        $data = Regency::where('kabupaten', $id)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);

        return response()->json($data);
    }
    public function kelurahan($id)
    {
        $data = Village::where('district_id', $id)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(20);

        return response()->json($data);
    }
}
