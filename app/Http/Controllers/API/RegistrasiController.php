<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Body;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrasiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                //rules untuk account
                'name'      => 'required',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|between:8,20|confirmed',
                'handphone' => 'required|numeric|min:11',
                //rules untuk alamat
                'alamat'      => 'required',
                'province_id' => 'required',
                'regency_id' => 'required',
                'district_id' => 'required',
                //rules untuk body
                'tinggi_badan'      => 'required|numeric|between:140,200',
                'berat_badan'      => 'required|numeric|between:35,100',
                'lingkar_perut' => 'required|numeric',
            ],
            [
                'tinggi_badan.min' => 'Minimal tinggi badan adalah 140 cm!',
                'tinggi_badan.max' => 'Maximal tinggi badan adalah 200 cm!',
                'berat_badan.min' => 'Minimal berat badan adalah 35 kg!',
                'berat_badan.max' => 'Maximal berat badan adalah 100 kg!',
                'handphone.numeric' => 'No. telfon tidak valid!',
                'handphone.min' => 'No. telfon tidak valid!. Digit kurang!'
            ],
        );

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //hitung body mass index atau index masa tubuh
        $weight = $request->berat_badan;
        $height = $request->tinggi_badan;
        $heightMeter = $height / 100;
        $countBMI = $weight / ($heightMeter * $heightMeter);
        //menentukan bmi 
        $kategoriBMI = '';
        if ($countBMI < 18.555) {
            $kategoriBMI = 'underweight';
        } elseif ($countBMI >= 18.555 && $countBMI <= 24.999) {
            $kategoriBMI = 'normal';
        } elseif ($countBMI >= 25 && $countBMI <= 29.999) {
            $kategoriBMI = 'overweight';
        } elseif ($countBMI >= 30) {
            $kategoriBMI = 'obesitas';
        }
        //hitung waist circumference
        $obesitasCentral = false;
        $waist = $request->lingkar_perut;
        if ($waist >= 102) {
            $obesitasCentral = true;
        }

        //menentukan termasuk tinggi, sedang atau pendek 
        $kategoriTinggi = '';
        if ($height > 170) {
            $kategoriTinggi = 'tinggi';
        } elseif ($height >= 160 && $height <= 170) {
            $kategoriTinggi = 'sedang';
        } elseif ($height < 160) {
            $kategoriTinggi = 'pendek';
        }
        $kategoriTubuh = 0;
        if ($kategoriBMI !== 'obesitas') {
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
                'message'    => "Maaf kategori BMI obesitas belum tersedia! ",
            ], 200);
        }
        //create user
        $user = User::create([
            'name'      => $request->name,
            'email'     => strtolower($request->email),
            'handphone' => $request->handphone,
            'password'  => bcrypt($request->password),
            'gender' => "Pria",
            'is_admin'
        ]);
        $alamat = Alamat::create([
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
        ]);

        $body = Body::create([
            'tinggi_badan'      => $height,
            'kategori_id' => $kategoriTubuh,
            'berat_badan'      => $weight,
            'lingkar_perut' => $waist,
            'user_id' => $user->id,

        ]);

        if ($body and $alamat) {
            User::where('id', $user->id)->update(['completed' => true]);
            return response()->json([
                'success' => true,
                'message' => "Registrasi berhasil!",
                'user'    => $user,
                'alamat' => $alamat,
                'body' => $body,
            ], 201);
        }
    }
}
