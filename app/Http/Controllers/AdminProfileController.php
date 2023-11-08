<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    function indexProfileAdmin($id)
    {

        $dataAdmin =

            DB::table("users")
                ->join("admin", function ($join) {
                    $join->on("users.id", "=", "admin.admin_id");
                })
                ->select("users.name", "users.email", "admin.*")
                ->where("admin.admin_id", "=", $id)
                ->first();


        return view('pages.admin.profileAdmin', ['id' => Auth::user()->id], compact('dataAdmin'));
    }

    function updateProfilAdmin($id)
    {
        $dataAdmin =

            DB::table("users")
                ->join("admin", function ($join) {
                    $join->on("users.id", "=", "admin.admin_id");
                })
                ->select("users.name", "users.email", "admin.*")
                ->where("admin.admin_id", "=", $id)
                ->first();


        return view('pages.admin.updateProfileAdmin', ['id' => Auth::user()->id], compact('dataAdmin'));
    }

    function storeProfileAdmin(Request $request, $id)
    {
        $profileAdmin = Admin::findOrFail($id);
        $fotoRequired = empty($profileAdmin->foto);
        $nikRequired = empty($profileAdmin->nik);
        
        $this->validate($request, [
            'foto' => $fotoRequired ? 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048' : 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        'nik' => $nikRequired ? 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048' : 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ], [
            'foto.required' => 'The foto field is required.',
            'foto.image' => 'The foto must be an image.',
            'foto.mimes' => 'The foto must be a file of type: jpg, png, jpeg, gif, svg.',
            'foto.max' => 'The foto must not be greater than 2048 kilobytes.',
            'nik.required' => 'The nik field is required.',
            'nik.image' => 'The nik must be an image.',
            'nik.mimes' => 'The nik must be a file of type: jpg, png, jpeg, gif, svg.',
            'nik.max' => 'The nik must not be greater than 2048 kilobytes.',
        ]);
        
        // Store the new foto if it exists
        if ($request->hasFile('foto')) {
            $image_path = $request->file('foto')->store('image', 'public');
            $profileAdmin->foto = $image_path;
        }

        // Store the new nik if it exists
        if ($request->hasFile('nik')) {
            $ktp_path = $request->file('nik')->store('image', 'public');
            $profileAdmin->nik = $ktp_path;
        }
        $profileAdmin->jenis_kelamin = $request->jenis_kelamin;
        $profileAdmin->no_handphone = $request->no_handphone;
        $profileAdmin->save();

        $userData = User::findOrFail($id);
        $userData->name = $request->name;
        $userData->email = $request->email;
        $userData->save();

        return redirect()->route('rProfileAdmin', ['id' => Auth::user()->id]);
    }
}