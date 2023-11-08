<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenghuniProfileController extends Controller
{
    function indexProfilePenghuni($id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            // dd(Auth::user()->id);
            $userId = Auth::id();


            $dataPenghuni =
                DB::table("users")
                    ->join("penghuni", function ($join) {
                        $join->on("users.id", "=", "penghuni.penghuni_id");
                    })
                    ->select("users.name", "users.email", "penghuni.*")
                    ->where("penghuni.penghuni_id", "=", $id)
                    ->first();
            return view('pages.penghuni.indexProfilePenghuni', compact('dataPenghuni'), ['id' => Auth::user()->id]);
        } else {
            abort(403, 'Unauthorized');
        }
    }
    function updateProfilePenghuni($id)
    {
        $dataPenghuni =
            DB::table("users")
                ->join("penghuni", function ($join) {
                    $join->on("users.id", "=", "penghuni.penghuni_id");
                })
                ->select("users.name", "users.email", "penghuni.*")
                ->where("penghuni.penghuni_id", "=", $id)
                ->first();

        return view('pages.penghuni.updateProfilePenghuni', ['id' => Auth::user()->id])->with('dataPenghuni', $dataPenghuni);


    }
    function storeProfilePenghuni(Request $request, $id)
    {
        $penghuniData = Penghuni::findOrFail($id);
        $fotoRequired = empty($penghuniData->foto);
        $nikRequired = empty($penghuniData->nik);
        $no_kkRequired = empty($penghuniData->no_kk);

        $this->validate($request, [
            'foto' => $fotoRequired ? 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048' : 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'nik' => $nikRequired ? 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048' : 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'no_kk' => $no_kkRequired ? 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048' : 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ], [
            'foto.required' => 'The Foto Profile field is required.',
            'foto.image' => 'The Foto Profile must be an image.',
            'foto.mimes' => 'The Foto Profile must be a file of type: jpg, png, jpeg, gif, svg.',
            'foto.max' => 'The Foto Profile must not be greater than 2048 kilobytes.',
            'nik.required' => 'The Kartu Tanda Penduduk field is required.',
            'nik.image' => 'The Kartu Tanda Penduduk must be an image.',
            'nik.mimes' => 'The Kartu Tanda Penduduk must be a file of type: jpg, png, jpeg, gif, svg.',
            'nik.max' => 'The nik must not be greater than 2048 kilobytes.',
            'no_kk.required' => 'The Kartu Keluarga field is required.',
            'no_kk.image' => 'The Kartu Keluarga must be an image.',
            'no_kk.mimes' => 'The Kartu Keluarga must be a file of type: jpg, png, jpeg, gif, svg.',
            'no_kk.max' => 'The Kartu Keluarga must not be greater than 2048 kilobytes.',
        ]);

        if ($request->hasFile('foto')) {
            $image_path = $request->file('foto')->store('image', 'public');
            $penghuniData->foto = $image_path;
        }

        // Store the new nik if it exists
        if ($request->hasFile('nik')) {
            $ktp_path = $request->file('nik')->store('image', 'public');
            $penghuniData->nik = $ktp_path;
        }

        if ($request->hasFile('no_kk')) {
            $kk_path = $request->file('no_kk')->store('image', 'public');
            $penghuniData->no_kk = $kk_path;
        }
        $penghuniData->jenis_kelamin = $request->jenis_kelamin;
        $penghuniData->no_handphone = $request->no_handphone;
        $penghuniData->save();

        $userData = User::findOrFail($id);
        $userData->name = $request->name;
        $userData->email = $request->email;
        $userData->save();

        return redirect()->route('rProfilePenghuni', ['id' => Auth::user()->id]);
    }
}