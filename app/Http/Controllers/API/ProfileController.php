<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Body;
use App\Models\Bukti;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function showAddress()
    {
        $dataAddress = Auth::user()->alamat;
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
        $detailAddress = Auth::user()->alamat->where('id', $id);
        return response()->json([
            'success' => true,
            'detail_alamat' => $detailAddress,
        ], 200);
    }

    public function updateAddress(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'alamat'      => 'required|unique',

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
        $updateUser = User::where('id', Auth::user()->id);
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $updateUser->update($data);
        return response()->json([
            'success' => true,
            'message' => "data diri berhasil diupdate!",
            'updated_user' => $updateUser->first(),
        ], 200);
    }

    public function showDataTubuh()
    {
        $dataTubuh = Auth::user()->body;
        return response()->json([
            'success' => true,
            'data_tubuh' => $dataTubuh,
        ], 200);
    }

    public function updateDataTubuh(Request $request)
    {
        $data = Auth::user()->dataTubuh;
        $update = Body::where('id', $data->id)->update([
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan
        ]);
        return response()->json([
            'success' => true,
            'message' => "data tubuh berhasil diupdate!",
            'data_tubuh' => $data,
        ], 200);
    }

    public function dataTransaction()
    {
        $dataTransaksi = Transaksi::where('user_id', Auth::user()->id)->where('paid', true)->with(
            'transactions',
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
        $dataTransaksi = Transaksi::where('user_id', Auth::user()->id)->where('id', $id)->with(
            'transactions',
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
    public function listToWait()
    {
        $data = Transaksi::where('user_id', Auth::user()->id)->where('paid', false)->with(
            'transactions',
            'ekspedisi',
            'transactions.detailProduct',
            'transactions.detailSize'
        )->get();
        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'list' => $data,
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
        return response()->json([
            'success' => true,
            'detailTransaksi' => $data,
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
            $imageName = Str::random(6) . '-' . $request->bukti_pembayaran->getClientOriginalName();
            $buktiImage =  $request->bukti_pembayaran->move(public_path('bukti_image'), $imageName);
            $bayar = Bukti::create([
                'bukti_pembayaran' => $imageName,
                'transaksi_id' => $request->transaksi_id,
            ]);
            if ($bayar) {
                Transaksi::where('id', $request->transaksi_id)->update([
                    'status_id' => 2,
                    'paid' => true,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => "Bukti pembayaran berhasil diupload!",
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Gambar belum dimasukkan!"
            ], 409);
        }
    }
}
