<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Body;
use App\Models\Bukti;
use App\Models\Detail;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function showAddress()
    {
        $dataAddress = Alamat::where('user_id', Auth::id())->with('province', 'kabupaten', 'kecamatan')->get();
        foreach ($dataAddress as $address) {
            $address->province->name = ucwords(strtolower($address->province->name));
            $address->kabupaten->name = ucwords(strtolower($address->kabupaten->name));
            $address->kecamatan->name = ucwords(strtolower($address->kecamatan->name));
        }

        if ($dataAddress->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data_address' => $dataAddress,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message'    => "Alamat anda kosong!",
                'data_alamat' => null
            ], 200);
        }
    }

    public function editAddress($id)
    {
        $detailAddress = Alamat::where('id', $id)
            ->with('province', 'kabupaten', 'kecamatan')->first();
        if ($detailAddress) {
            return response()->json([
                'success' => true,
                'detail_alamat' => $detailAddress,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'data alamat tidak ditemukan!'
            ], 200);
        }
    }

    public function updateAddress(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'alamat'      => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Alamat::where('id', $id)->update([
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'success' => true,
            'message' => "data alamat berhasil diupdate!",
        ], 200);
    }

    public function deleteAddress($id)
    {
        $data = Auth::user()->alamat->count();
        if ($data > 1) {
            Alamat::find($id)->delete();
            return response()->json([
                'success' => true,
                'message' => "data alamat berhasil dihapus!",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Alamat gagal dihapus!",
            ], 200);
        }
    }

    public function profile()
    {
        return response()->json([
            'success' => true,
            'data_user' => Auth::user(),
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|unique:users,email,' . Auth::user()->id,
                'password' => 'required|min:8|confirmed'
            ],
            [
                'email.unique' => 'Email sudah digunakan. Gunakan email yang lain',
                'email.email' => 'Email tidak valid',
                'password.min' => 'Minimal password 8 karakter',
                'password.confirmed' => 'Password tidak sama!',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $updateUser = User::where('id', Auth::user()->id);
        $updateUser->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response()->json([
            'success' => true,
            'message' => "data diri berhasil diupdate!",
        ], 200);
    }

    public function showDataTubuh()
    {
        $dataTubuh = Auth::user()->body;
        if ($dataTubuh) {
            return response()->json([
                'success' => true,
                'data_tubuh' => $dataTubuh,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data_tubuh' => null,
                'message' => 'data tubuh anda kosong!',
            ], 200);
        }
    }

    public function updateDataTubuh(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                //rules untuk body
                'tinggi_badan'      => 'required|numeric|min:140|max:200',
                'berat_badan'      => 'required|numeric|min:35|max:100',
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

        $data = Auth::user()->body;
        $weight = $request->berat_badan;
        $height = $request->tinggi_badan;
        $heightMeter = $height / 100;
        $countBMI = $weight / ($heightMeter * $heightMeter);

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

        $update = Body::where('id', $data->id)->update([
            'tinggi_badan' => $height,
            'berat_badan' => $weight,
            'lingkar_perut' => $waist,
            'kategori_id' => $kategoriTubuh,
        ]);
        return response()->json([
            'success' => true,
            'message' => "data tubuh berhasil diupdate!",
            'data_tubuh' => $update,
        ], 200);
    }

    public function dataTransaction()
    {
        $dataTransaksi = Transaksi::where('user_id', Auth::user()->id)
            ->where('paid', true)->with(
                'transactions',
                'statusName',
                'ekspedisi',
                'transactions.detailProduct',
                'transactions.detailSize'
            )->get();

        if ($dataTransaksi->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'all_transaction' => $dataTransaksi,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'all_transaction' => null,
                'messaage' => 'Tidak ada transaksi yang berjalan!',
            ], 200);
        }
    }

    public function invoice($id)
    {
        $dataTransaksi = Transaksi::where('user_id', Auth::user()->id)
            ->where('id', $id)->with(
                'userAddress.province',
                'userAddress.kabupaten',
                'userAddress.kecamatan',
                'ekspedisi',
                'transactions',
                'transactions.detailProduct',
                'transactions.detailSize'
            )->get()->pluck('transactions', 'userAddress')->flatten();
        if ($dataTransaksi->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'all_transaction' => $dataTransaksi,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'all_transaction' => null,
                'messaage' => 'Tidak ada transaksi yang berjalan!',
            ], 200);
        }
    }
    public function listToWait()
    {
        $data = Transaksi::where('user_id', Auth::user()->id)
            ->where('paid', false)->with(
                'transactions',
                'ekspedisi',
                'transactions.detailProduct',
                'transactions.detailSize',
            )->get();
        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'dataTransaksi' => $data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'list' => null,
                'messaage' => 'Tidak ada transaksi yang menunggu untuk dibayar!',
            ], 200);
        }
    }

    public function uploadBukti($id)
    {
        $data = Transaksi::where('id', $id)->where('user_id', Auth::user()->id)->with(
            'transactions',
            'ekspedisi',
            'transactions.detailProduct',
            'transactions.detailSize'
        )->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'detailTransaksi' => $data,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'detailTransaksi' => null,
            'pesan' => 'data transaksi menunggu pembayaran tidak ditemukan',
        ], 200);
    }
    public function storeBukti(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran'      => 'required|mimes:jpg,bmp,png',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if ($request->has('bukti_pembayaran')) {
            $imageName = Str::random(6) . '-' . $request->bukti_pembayaran
                ->getClientOriginalName();
            $buktiImage =  $request->bukti_pembayaran
                ->move(public_path('../../wearit-frontend/public/assets/bukti'), $imageName);

            Transaksi::where('id', $request->transaksi_id)->update([
                'status_id' => 2,
                'bukti_pembayaran' => $imageName,
                'paid' => true,
            ]);
            return response()->json([
                'success' => true,
                'message' => "Bukti pembayaran berhasil diupload!",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Gambar belum dimasukkan!"
            ], 409);
        }
    }

    public function finished($id)
    {
        $updateStatus = Transaksi::where('id', $id)->update([
            'status_id' => 7,
            'end_transaction' => Carbon::now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diselesaikan',
            'updated' => $updateStatus,
        ], 200);
    }
}
