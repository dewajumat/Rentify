<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminPemeliharaanController extends Controller
{
    function indexPemeliharaanAdmin($id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            // dd(Auth::user()->id);
            $dataPemeliharaan =
                DB::table("users")
                    ->join("penghuni", function ($join) {
                        $join->on("users.id", "=", "penghuni.penghuni_id");
                    })
                    ->join("bilik", function ($join) {
                        $join->on("penghuni.penghuni_id", "=", "bilik.penghuni_id");
                    })
                    ->join("properti", function ($join) {
                        $join->on("bilik.properti_id", "=", "properti.properti_id");
                    })
                    ->join("pemeliharaan", function ($join) {
                        $join->on("bilik.bilik_id", "=", "pemeliharaan.bilik_id");
                    })
                    ->select("users.name", "penghuni.penghuni_id", "properti.jenis_properti", "properti.alamat", "bilik.bilik_id", "bilik.no_bilik", "pemeliharaan.*")
                    ->where("properti.admin_id", "=", $id)
                    ->get();


            return view('pages.admin.indexPemeliharaan', ['id' => Auth::user()->id, 'dataPemeliharaan' => $dataPemeliharaan]);
        }
    }

    function addPemeliharaanAdmin($id, $penghuni_id, $bilik_id, $pemeliharaan_id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $dataPemeliharaanBilik =
                DB::table("users")
                    ->join("penghuni", function ($join) {
                        $join->on("users.id", "=", "penghuni.penghuni_id");
                    })
                    ->join("bilik", function ($join) {
                        $join->on("penghuni.penghuni_id", "=", "bilik.penghuni_id");
                    })
                    ->join("properti", function ($join) {
                        $join->on("bilik.properti_id", "=", "properti.properti_id");
                    })
                    ->join("pemeliharaan", function ($join) {
                        $join->on("bilik.bilik_id", "=", "pemeliharaan.bilik_id");
                    })
                    ->select("users.name", "penghuni.penghuni_id", "properti.jenis_properti", "properti.alamat", "bilik.no_bilik", "pemeliharaan.*")
                    ->where("pemeliharaan.pemeliharaan_id", "=", $pemeliharaan_id)
                    ->get();

            return view('pages.admin.addPemeliharaan', ['id' => Auth::user()->id, 'dataPemeliharaanBilik' => $dataPemeliharaanBilik, 'penghuni_id' => $penghuni_id, 'bilik_id' => $bilik_id, 'pemeliharaan_id' => $pemeliharaan_id]);
        }
    }

    function storePemeliharaanAdmin(Request $request, $id, $penghuni_id, $bilik_id, $pemeliharaan_id)
    {
        if (Auth::check() && Auth::user()->id == $id) {

            if ($request->input('submitAction') === 'konfirmasi') {
                // Perform action for "konfirmasi" button
                $statPemeliharaan = Pemeliharaan::findOrFail($pemeliharaan_id);
                $statPemeliharaan->status_pemeliharaan = "Diproses";
                $statPemeliharaan->save();

                return redirect()->route('rmadmin', $id);
            } elseif ($request->input('submitAction') === 'dataBayar') {
                // Perform action for "dataBayar" button
                $ttl = $request->total;
                $cleanttl = str_replace(['Rp', '.', ','], '', $ttl);
                $total_clean = (int) $cleanttl;

                $statPemeliharaan = Pemeliharaan::findOrFail($pemeliharaan_id);
                $statPemeliharaan->tgl_selesai = $request->tgl_selesai;
                $statPemeliharaan->status_pemeliharaan = "Selesai";
                $statPemeliharaan->total = $total_clean;
                $statPemeliharaan->save();

                return redirect()->route('rmadmin', $id);
            } elseif ($request->input('submitAction') === 'tolak') {

                $request->validate([
                    'ket_penolakan' => 'required'
                ], [
                    'ket_penolakan.required' => 'Silahkan masukan alasan penolakan',
                ]);
                $statPemeliharaan = Pemeliharaan::findOrFail($pemeliharaan_id);
                $statPemeliharaan->status_pemeliharaan = "Ditolak";
                $statPemeliharaan->ket_penolakan = $request->ket_penolakan;
                $statPemeliharaan->save();

                return redirect()->route('rmadmin', $id);
            }

        }
    }

    function detailPemeliharaanAdmin($id, $pemeliharaan_id){
        $dataPemeliharaanBilik =
                DB::table("users")
                    ->join("penghuni", function ($join) {
                        $join->on("users.id", "=", "penghuni.penghuni_id");
                    })
                    ->join("bilik", function ($join) {
                        $join->on("penghuni.penghuni_id", "=", "bilik.penghuni_id");
                    })
                    ->join("properti", function ($join) {
                        $join->on("bilik.properti_id", "=", "properti.properti_id");
                    })
                    ->join("pemeliharaan", function ($join) {
                        $join->on("bilik.bilik_id", "=", "pemeliharaan.bilik_id");
                    })
                    ->select("users.name", "penghuni.penghuni_id", "properti.jenis_properti", "properti.alamat", "bilik.no_bilik", "pemeliharaan.*")
                    ->where("pemeliharaan.pemeliharaan_id", "=", $pemeliharaan_id)
                    ->get();

            return view('pages.admin.detailPemeliharaan', ['id' => Auth::user()->id, 'dataPemeliharaanBilik' => $dataPemeliharaanBilik,'pemeliharaan_id' => $pemeliharaan_id]);
    }
}
