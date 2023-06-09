<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Body;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BodyController extends Controller
{
    public function store(Request $request)
    {

        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'tinggi_badan'      => 'required',
            'berat_badan'      => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'lingkar_perut' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //hitung body mass index atau index masa tubuh
        $weight = $request->berat_badan;
        $height = $request->tinggi_badan;
        $heightMeter = $request->tinggi_badan / 100;
        $countBMI = $weight / ($heightMeter * $heightMeter);

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
        if ($waist >= 102) {
            $obesitasCentral = true;
        }

        //menentukan termasuk tinggi, sedang atau pendek 
        $kategoriTinggi = '';
        $t_badan = $request->tinggi_badan;
        if ($t_badan > 170) {
            $kategoriTinggi = 'tinggi';
        } elseif ($t_badan > 160 && $t_badan < 170) {
            $kategoriTinggi = 'sedang';
        } elseif ($t_badan < 160) {
            $kategoriTinggi = 'pendek';
        }
        $kategoriTubuh = 0;
        if ($kategoriBMI !== 'error') {
            if ($kategoriBMI == 'underweight' && !$obesitasCentral && $kategoriTinggi == 'tinggi') {
                $kategoriTubuh = 5;
            } elseif ($kategoriBMI == 'underweight' && !$obesitasCentral && $kategoriTinggi == 'sedang') {
                $kategoriTubuh = 3;
            } elseif ($kategoriBMI == 'underweight' && !$obesitasCentral && $kategoriTinggi == 'pendek') {
                $kategoriTubuh = 1;
            } elseif ($kategoriBMI == 'underweight' && $obesitasCentral && $kategoriTinggi == 'tinggi') {
                $kategoriTubuh = 6;
            } elseif ($kategoriBMI == 'underweight' && $obesitasCentral && $kategoriTinggi == 'sedang') {
                $kategoriTubuh = 4;
            } elseif ($kategoriBMI == 'underweight' && $obesitasCentral && $kategoriTinggi == 'pendek') {
                $kategoriTubuh = 2;
            } elseif ($kategoriBMI == 'normal' && !$obesitasCentral && $kategoriTinggi == 'tinggi') {
                $kategoriTubuh = 11;
            } elseif ($kategoriBMI == 'normal' && !$obesitasCentral && $kategoriTinggi == 'sedang') {
                $kategoriTubuh = 9;
            } elseif ($kategoriBMI == 'normal' && !$obesitasCentral && $kategoriTinggi == 'pendek') {
                $kategoriTubuh = 7;
            } elseif ($kategoriBMI == 'normal' && $obesitasCentral && $kategoriTinggi == 'tinggi') {
                $kategoriTubuh = 12;
            } elseif ($kategoriBMI == 'normal' && $obesitasCentral && $kategoriTinggi == 'sedang') {
                $kategoriTubuh = 10;
            } elseif ($kategoriBMI == 'normal' && $obesitasCentral && $kategoriTinggi == 'pendek') {
                $kategoriTubuh = 8;
            } elseif ($kategoriBMI == 'overweight' && !$obesitasCentral && $kategoriTinggi == 'tinggi') {
                $kategoriTubuh = 17;
            } elseif ($kategoriBMI == 'overweight' && !$obesitasCentral && $kategoriTinggi == 'sedang') {
                $kategoriTubuh = 15;
            } elseif ($kategoriBMI == 'overweight' && !$obesitasCentral && $kategoriTinggi == 'pendek') {
                $kategoriTubuh = 13;
            } elseif ($kategoriBMI == 'overweight' && $obesitasCentral && $kategoriTinggi == 'tinggi') {
                $kategoriTubuh = 18;
            } elseif ($kategoriBMI == 'overweight' && $obesitasCentral && $kategoriTinggi == 'sedang') {
                $kategoriTubuh = 16;
            } elseif ($kategoriBMI == 'overweight' && $obesitasCentral && $kategoriTinggi == 'pendek') {
                $kategoriTubuh = 14;
            }
        } else {
            return response()->json([
                'success' => false,
                'message'    => "Sorry invalid data ",
            ], 201);
        }
        $checkData = Body::where('user_id', $user_id)->count();
        if ($checkData == 0) {
            $dataTubuh = Body::create([
                'tinggi_badan'      => $height,
                'kategori_id' => $kategoriTubuh,
                'berat_badan'      => $weight,
                'lingkar_perut' => $waist,
                'user_id' => $user_id,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Anda sudah pernah mengisi data tubuh!",
            ], 200);
        }
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
