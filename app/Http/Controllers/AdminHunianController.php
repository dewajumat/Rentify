<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bilik;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminHunianController extends Controller
{
    function indexAdminId($id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $authId = Auth::user()->id;

            $forMessage1 = Admin::find($authId);
            $allNull = (
                is_null($forMessage1->jenis_kelamin) &&
                is_null($forMessage1->nik) &&
                is_null($forMessage1->no_handphone)
            );
            if ($allNull) {
                $allFieldsNull = true;
            } else {
                $allFieldsNull = false;
            }
            $adminProperti =
                DB::table("properti")
                    ->join("admin", function ($join) {
                        $join->on("properti.admin_id", "=", "admin.admin_id");
                    })
                    ->select("properti.*", "properti.admin_id")
                    ->where("admin.admin_id", "=", $id)
                    ->get();

            $dataPenghuni =
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
                    ->join("admin", function ($join) {
                        $join->on("properti.admin_id", "=", "admin.admin_id");
                    })
                    ->select("users.name", "users.email", "penghuni.*", "properti.*", "bilik.*")
                    ->where("admin.admin_id", "=", $authId)
                    ->get();
            $penghuniId = $dataPenghuni->pluck('penghuni_id');
            return view('pages.admin.indexAdmin', compact('dataPenghuni', 'penghuniId', 'adminProperti', 'allFieldsNull'), ['id' => Auth::user()->id]);
        } else {
            abort(403, 'Unauthorized');
        }
    }

    function addPenghuniAdmin(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $userId = (Auth::user()->id);
            $penghuniId = $request->input('dropdown');

            $namaCalonPenghuni =
                DB::table("users")
                    ->join("penghuni", function ($join) {
                        $join->on("users.id", "=", "penghuni.penghuni_id");
                    })
                    ->select("penghuni.*", "users.name")
                    ->get();

            $dataProperti =
                DB::table("properti")
                    ->join("admin", function ($join) {
                        $join->on("properti.admin_id", "=", "admin.admin_id");
                    })
                    ->where("admin.admin_id", "=", $userId)
                    ->get();

            $dataBilik =
                DB::table("bilik")
                    ->join("properti", function ($join) {
                        $join->on("bilik.properti_id", "=", "properti.properti_id");
                    })
                    ->join("admin", function ($join) {
                        $join->on("properti.admin_id", "=", "admin.admin_id");
                    })
                    ->select("bilik.no_bilik", "properti.properti_id")
                    ->where("admin.admin_id", "=", $userId)
                    ->get();
            return view('pages.admin.addPenghuni1', ['namaCalonPenghuni' => $namaCalonPenghuni, 'penghuniId' => $penghuniId, 'dataProperti' => $dataProperti, 'dataBilik' => $dataBilik, 'id' => Auth::user()->id]);
        }
    }

    function storePenghuniAdmin(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->id == $id) {

            $request->validate([
                'dropdown' => 'required'
            ], [
                'dropdown.required' => 'Nama penghuni tidak boleh kosong',
            ]);

            $tp = $request->input('total_pembayaran');
            $cleanhtp = str_replace(['Rp', '.', ','], '', $tp);
            $total_pembayaran_clean = (int) $cleanhtp;

            $penghuniId = $request->input('dropdown');

            $bilikData = Bilik::create([
                'penghuni_id' => $penghuniId,
                'properti_id' => $request->properti_id,
                'no_bilik' => $request->jumlah_bilik,
                'tipe_hunian' => $request->tipe_hunian,
                'status_hunian' => "Perlu Konfirmasi",
                'total_pembayaran' => $total_pembayaran_clean,
            ]);

            return redirect()->route('radmin', $id);
        }
    }

    function canceledHunian($id, $bilik_id)
    {
        $dataBilikProp =
            DB::table("bilik")
                ->join("properti", function ($join) {
                    $join->on("bilik.properti_id", "=", "properti.properti_id");
                })
                ->select("bilik.no_bilik", "properti.properti_id", "properti.jenis_properti", "bilik.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->get();

        return view('pages.admin.canceledHunian', compact('dataBilikProp'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);
    }
    function deleteBilikPenghuni($id, $bilik_id)
    {
        $dataBilik = Bilik::find($bilik_id);
        // dd($dataBilik);
        if ($dataBilik) {
            $dataBilik->delete();
            Session::flash('successMessage', 'Data bilik berhasil dihapus');
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    function detailBilik($id, $bilik_id)
    {
        $dataBilik =
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
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->select("users.name", "users.email", "penghuni.*", "bilik.*", "properti.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->where("admin.admin_id", "=", $id)
                ->first();

        $dataPembayaranBilik =
            DB::table("users")
                ->join("bilik", function ($join) {
                    $join->on("users.id", "=", "bilik.penghuni_id");
                })
                ->join("pembayaran", function ($join) {
                    $join->on("bilik.bilik_id", "=", "pembayaran.bilik_id");
                })
                ->join("properti", function ($join) {
                    $join->on("bilik.properti_id", "=", "properti.properti_id");
                })
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->select("users.name", "pembayaran.*", "bilik.*", "properti.jenis_properti", "properti.alamat")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->where("admin.admin_id", "=", $id)
                ->get();

        return view('pages.admin.detailBilik', compact('dataBilik', 'dataPembayaranBilik'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);

    }
    function konfirmasiPembayaran($id, $penghuni_id, $bilik_id, $pembayaran_id)
    {
        $dataPenghuni =
            DB::table("users")
                ->join("penghuni", function ($join) {
                    $join->on("users.id", "=", "penghuni.penghuni_id");
                })
                ->join("bilik", function ($join) {
                    $join->on("penghuni.penghuni_id", "=", "bilik.penghuni_id");
                })
                ->join("pembayaran", function ($join) {
                    $join->on("bilik.bilik_id", "=", "pembayaran.bilik_id");
                })
                ->join("properti", function ($join) {
                    $join->on("bilik.properti_id", "=", "properti.properti_id");
                })
                ->select("users.name", "users.email", "penghuni.*", "bilik.*", "properti.jenis_properti", "properti.alamat", "pembayaran.*")
                ->where("pembayaran.pembayaran_id", "=", $pembayaran_id)
                ->get();

        return view('pages.admin.konfirmasiPembayaran', ['id' => Auth::user()->id, 'penghuni_id' => $penghuni_id, 'bilik_id' => $bilik_id, 'pembayaran_id' => $pembayaran_id], compact('dataPenghuni'));    
    }

    function sKonfirmasiPembayaran(Request $request, $id, $penghuni_id, $bilik_id, $pembayaran_id)
    {

        if ($request->input('submitAction') === 'konfirmasi') {
            $dataBilik = Bilik::findOrFail($bilik_id);
            $dataBilik->status_hunian = "Aktif";
            $dataBilik->status_pembayaran = "Terbayar";
            $dataBilik->isFilled = "true";
            $dataBilik->save();

            $dataPembayaran = Pembayaran::findOrFail($pembayaran_id);
            $dataPembayaran->stat_pembayaran = "Terbayar";
            $dataPembayaran->save();

            return redirect()->route('radmin', $id);
        } elseif ($request->input('submitAction') === 'tolak') {

            $request->validate([
                'ket_penolakan' => 'required'
            ], [
                'ket_penolakan.required' => 'Silahkan masukan alasan penolakan',
            ]);
            $dataBilik = Bilik::findOrFail($bilik_id);
            $dataBilik->status_hunian = "Pending";
            $dataBilik->status_pembayaran = "Pending";
            $dataBilik->save();

            $dataPembayaran = Pembayaran::findOrFail($pembayaran_id);
            $dataPembayaran->ket_penolakan = $request->ket_penolakan;
            $dataPembayaran->stat_pembayaran = "Pending";
            $dataPembayaran->save();

            return redirect()->route('radmin', $id);
        }
    }
}
