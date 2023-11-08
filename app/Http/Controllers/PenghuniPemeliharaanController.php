<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenghuniPemeliharaanController extends Controller
{
    function indexPemeliharaan($id)
    {
        $dataPemeliharaan =
            DB::table("properti")
                ->join("bilik", function ($join) {
                    $join->on("properti.properti_id", "=", "bilik.properti_id");
                })
                ->join("pemeliharaan", function ($join) {
                    $join->on("pemeliharaan.bilik_id", "=", "bilik.bilik_id");
                })
                ->join("penghuni", function ($join) {
                    $join->on("bilik.penghuni_id", "=", "penghuni.penghuni_id");
                })
                ->select("properti.*", "bilik.no_bilik", "pemeliharaan.*", "penghuni.*")
                ->where("penghuni.penghuni_id", "=", $id)
                ->get();

        $dataProperti =
            DB::table("properti")
                ->join("bilik", function ($join) {
                    $join->on("properti.properti_id", "=", "bilik.properti_id");
                })
                ->join("penghuni", function ($join) {
                    $join->on("bilik.penghuni_id", "=", "penghuni.penghuni_id");
                })
                ->select("properti.*", "bilik.*", "penghuni.*")
                ->where("penghuni.penghuni_id", "=", $id)
                ->get();

        return view('pages.penghuni.indexPemeliharaan', compact('dataPemeliharaan', 'dataProperti'), ['id' => Auth::user()->id]);
    }

    function addPemeliharaan(Request $request, $id)
    {
        $bilik_id = $request->input('dropdown');
        $dataBilik =
            DB::table("properti")
                ->join("bilik", function ($join) {
                    $join->on("properti.properti_id", "=", "bilik.properti_id");
                })
                ->select("properti.jenis_properti", "properti.alamat", "bilik.no_bilik")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->get();

        return view('pages.penghuni.addPemeliharaan', compact('dataBilik'), ['id' => Auth::user()->id, 'dataBilik' => $dataBilik, 'bilik_id' => $bilik_id]);
    }

    function storePemeliharaan(Request $request, $id, $bilik_id)
    {
        $this->validate($request, [
            'gambar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $image_path = $request->file('gambar')->store('image', 'public');
        $dataPemeliharaan = Pemeliharaan::create([
            'bilik_id' => $bilik_id,
            'tgl_pengajuan' => $request->tgl_pengajuan,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $image_path,
            'status_pemeliharaan' => "Pending",
        ]);

        return redirect()->route('rIndexPemeliharaan', ['id' => Auth::user()->id]);
    }

    function detailPemeliharaan($id, $pemeliharaan_id)
    {
        $dataPemeliharaanBilik =
            DB::table("bilik")
                ->join("properti", function ($join) {
                    $join->on("bilik.properti_id", "=", "properti.properti_id");
                })
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->join("users", function ($join) {
                    $join->on("users.id", "=", "admin.admin_id");
                })
                ->join("pemeliharaan", function ($join) {
                    $join->on("bilik.bilik_id", "=", "pemeliharaan.bilik_id");
                })
                ->select("users.name", "properti.jenis_properti", "properti.alamat", "bilik.no_bilik", "pemeliharaan.*")
                ->where("pemeliharaan.pemeliharaan_id", "=", $pemeliharaan_id)
                ->get();


        return view('pages.penghuni.detailPemeliharaan', [
            'id' => Auth::user()->id,
            'dataPemeliharaanBilik' => $dataPemeliharaanBilik,
            'pemeliharaan_id' => $pemeliharaan_id
        ]);
    }
}