<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DataTubuh;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DataTubuhController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $validator = Validator::make($request->all(), [
            'tinggi_badan'      => 'required',
            'berat_badan'      => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $dataTubuh = DataTubuh::create([
            'tinggi_badan'      => $request->tinggi_badan,
            'berat_badan'      => $request->berat_badan,
            'user_id' => $user_id,
        ]);

        //return response JSON user is created
        if ($dataTubuh) {
            User::where('id', $user_id)->update(['completed' => true]);
            return response()->json([
                'success' => true,
                'dataTubuh'    => $dataTubuh,
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }
}
