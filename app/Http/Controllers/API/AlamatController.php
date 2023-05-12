<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $validator = Validator::make($request->all(), [
            'alamat'      => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $alamat = Alamat::create([
            'alamat'      => $request->alamat,
            'user_id' => $user_id,
        ]);

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
}
