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
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'tinggi_badan'      => 'required',
            'berat_badan'      => 'required',
            'lingkar_perut' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //hitung body mass index atau index masa tubuh
        $weight = $request->berat_badan;
        $height = $request->tinggi_badan / 100;
        $countBMI = $weight / ($height * $height);

        $kategoriBMI = '';
        if ($countBMI < 18.5) {
            $kategoriBMI = 'underweight';
        }
        if ($countBMI >= 18.5 and $countBMI <= 24.9) {
            $kategoriBMI = 'normal';
        }
        if ($countBMI >= 25 and $countBMI <= 29.9) {
            $kategoriBMI = 'overweight';
        }
        if ($countBMI >= 30) {
            $kategoriBMI = 'error';
        }
        //hitung waist circumference
        $obesitasCentral = false;
        $waist = $request->lingkar_perut;

        if (Auth::user()->gender == 'pria' && $waist >= 102) {
            $obesitasCentral = true;
        } elseif (Auth::user()->gender == 'wanita' && $waist >= 88) {
            $obesitasCentral = true;
        }

        //menentukan termasuk tinggi atau tidak
        $kategoriTinggi = false;
        $t_badan = $request->tinggi_badan;
        if (Auth::user()->gender == 'pria' and $t_badan > 170) {
            $kategoriTinggi = true;
        } elseif (Auth::user()->gender == 'wanita' and $t_badan > 160) {
            $kategoriTinggi = true;
        }

        $kategoriTubuh = 0;
        if ($kategoriBMI == 'underweight' && !$obesitasCentral && !$kategoriTinggi) {
            $kategoriTubuh = 1;
        } elseif ($kategoriBMI == 'underweight' && $obesitasCentral && $kategoriTinggi) {
            $kategoriTubuh = 2;
        } elseif ($kategoriBMI == 'underweight' && !$obesitasCentral && $kategoriTinggi) {
            $kategoriTubuh = 3;
        } elseif ($kategoriBMI == 'underweight' && $obesitasCentral && !$kategoriTinggi) {
            $kategoriTubuh = 4;
        } elseif ($kategoriBMI == 'normal' && $obesitasCentral && !$kategoriTinggi) {
            $kategoriTubuh = 5;
        } elseif ($kategoriBMI == 'normal' && !$obesitasCentral && $kategoriTinggi) {
            $kategoriTubuh = 6;
        } elseif ($kategoriBMI == 'normal' && $obesitasCentral && $kategoriTinggi) {
            $kategoriTubuh = 7;
        } elseif ($kategoriBMI == 'normal' && !$obesitasCentral && !$kategoriTinggi) {
            $kategoriTubuh = 8;
        } elseif ($kategoriBMI == 'overweight' && $obesitasCentral && !$kategoriTinggi) {
            $kategoriTubuh = 9;
        } elseif ($kategoriBMI == 'overweight' && !$obesitasCentral && $kategoriTinggi) {
            $kategoriTubuh = 10;
        } elseif ($kategoriBMI == 'overweight' && $obesitasCentral && $kategoriTinggi) {
            $kategoriTubuh = 11;
        } elseif ($kategoriBMI == 'overweight' && !$obesitasCentral && !$kategoriTinggi) {
            $kategoriTubuh = 12;
        }
        dd($kategoriTubuh);
        $dataTubuh = DataTubuh::create([
            'tinggi_badan'      => $weight,
            'model_id' => $kategoriTubuh,
            'berat_badan'      => $weight,
            'lingkar_perut' => $waist,
            'user_id' => $user_id,
        ]);

        if ($dataTubuh) {
            User::where('id', $user_id)->update(['completed' => true]);
            return response()->json([
                'success' => true,
                'dataTubuh'    => $dataTubuh,
            ], 201);
        }
        return response()->json([
            'success' => false,
        ], 409);
    }
}
