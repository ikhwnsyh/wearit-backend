<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\DataTubuh;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showAddress()
    {
        // $alamat = Alamat::where('id', 2)->first()->user;
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
        $dataUser = Auth::user();
        return response()->json([
            'success' => true,
            'data_user' => $dataUser,
        ], 200);
    }

    public function editProfile()
    {
        $dataUser = Auth::user();
        return response()->json([
            'success' => true,
            'data_user' => $dataUser,
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $dataUser = Auth::user();
        $updateUser = User::where('id', $dataUser->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        return response()->json([
            'success' => true,
            'message' => "data diri berhasil diupdate!",
            'updated_user' => $updateUser,
        ], 200);
    }

    public function showDataTubuh()
    {
        $dataTubuh = Auth::user()->dataTubuh;
        return response()->json([
            'success' => true,
            'data_tubuh' => $dataTubuh,
        ], 200);
    }

    public function updateDataTubuh(Request $request)
    {
        $data = Auth::user()->dataTubuh;
        $update = DataTubuh::where('id', $data->id)->update([
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan
        ]);
        return response()->json([
            'success' => true,
            'message' => "data tubuh berhasil diupdate!",
            'data_tubuh' => $data,
        ], 200);
    }
}
