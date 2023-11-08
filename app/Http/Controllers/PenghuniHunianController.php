<?php

namespace App\Http\Controllers;

use App\Models\Bilik;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenghuniHunianController extends Controller
{
    function indexPenghuniId($id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $authId = Auth::user()->id;
            $dataIndexPenghuni =
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
                    ->select("users.name", "users.email", "penghuni.*", "bilik.*", "properti.jenis_properti", "properti.alamat")
                    ->where("penghuni.penghuni_id", "=", $authId)
                    ->get();

            $bilikPenghuni =

                DB::table("bilik")
                    ->join("penghuni", function ($join) {
                        $join->on("bilik.penghuni_id", "=", "penghuni.penghuni_id");
                    })
                    ->select("bilik.*")
                    ->where("penghuni.penghuni_id", "=", $authId)
                    ->get();

            $forMessage = Penghuni::find($penghuni_id = $id);
            $allNull = (
                is_null($forMessage->jenis_kelamin) &&
                is_null($forMessage->nik) &&
                is_null($forMessage->no_kk) &&
                is_null($forMessage->no_handphone)
            );

            if ($allNull) {
                $allFieldsNull = true;
            } else {
                $allFieldsNull = false;
            }

            return view('pages.penghuni.homePenghuni', compact('dataIndexPenghuni', 'allFieldsNull', 'bilikPenghuni'), ['id' => Auth::user()->id]);

        } else {
            abort(403, 'Unauthorized');
        }
    }

    function detailBilikPenghuni($id, $bilik_id)
    {
        $dataBilik =
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
                ->select("users.name", "bilik.*", "properti.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->get();

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
                ->select("pembayaran.*", "bilik.*", "properti.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->where("bilik.penghuni_id", "=", $id)
                ->get();

        return view('pages.penghuni.detailHunian', compact('dataBilik', 'dataPembayaranBilik'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);
    }

    function konfirmasiMenghuni($id, $bilik_id)
    {
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
                ->select("users.name", "users.email", "users.role", "penghuni.*", "bilik.*", "properti.*")
                ->where("penghuni.penghuni_id", "=", $id)
                ->get();

        $dataBilikProp =
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
                ->select("users.name", "properti.*", "bilik.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->get();

        return view('pages.penghuni.formKonfirmasi', compact('dataPenghuni', 'dataBilikProp'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);
    }

    function storeKonfirmasiMenghuni(Request $request, $id, $bilik_id)
    {
        $submit = $request->input('buttonType');
        if ($submit === 'submitButton') {
            $dataBilik = Bilik::findOrFail($bilik_id);
            $dataBilik->status_hunian = "Pending";
            $dataBilik->status_pembayaran = "Belum bayar";
            $dataBilik->isFilled = "true";
            $dataBilik->save();

        } elseif ($submit === 'cancelButton') {
            $request->validate([
                'ket_pembatalan' => 'required'
            ], [
                'ket_pembatalan.required' => 'Keterangan pembatalan wajib diisi',
            ]);
            $dataBilik = Bilik::findOrFail($bilik_id);
            $dataBilik->isFilled = "false";
            $dataBilik->status_hunian = "Dibatalkan";
            $dataBilik->ket_pembatalan = $request->ket_pembatalan;
            $dataBilik->save();
        }
        return redirect()->route('rpenghuni', ['id' => Auth::user()->id]);
    }

    function formPembayaran($id, $bilik_id)
    {
        $authId = Auth::user()->id;

        $dataPembayaranBilik =
            DB::table("properti")
                ->join("bilik", function ($join) {
                    $join->on("properti.properti_id", "=", "bilik.properti_id");
                })
                ->select("properti.jenis_properti", "properti.alamat", "bilik.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->get();

        return view('pages.penghuni.formPembayaran', compact('dataPembayaranBilik'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);
    }

    function sFormPembayaran(Request $request, $id, $bilik_id)
    {
        $this->validate($request, [
            'bukti_pembayaran' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $image_path = $request->file('bukti_pembayaran')->store('image', 'public');

        $dataPembayaran = Pembayaran::create();
        $dataPembayaran->bilik_id = $bilik_id;
        $dataPembayaran->penghuni_id = $id;
        $dataPembayaran->bukti_pembayaran = $image_path;
        $dataPembayaran->bulan_start_terbayar = $request->bulan_start_terbayar;
        $dataPembayaran->bulan_end_terbayar = $request->bulan_end_terbayar;
        $dataPembayaran->tgl_pembayaran = $request->tgl_pembayaran;
        $dataPembayaran->stat_pembayaran = "Diproses";
        $dataPembayaran->save();

        $dataBilik = Bilik::findOrFail($bilik_id);
        $dataBilik->status_hunian = "Diproses";
        $dataBilik->status_pembayaran = "Diproses";
        $dataBilik->save();

        return redirect()->route('rpenghuni', ['id' => Auth::user()->id]);
    }
    function rejectedPayment($id, $bilik_id, $pembayaran_id)
    {
        // dd($pembayaran_id);
        $dataPembayaranPenghuni =
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
                ->select("users.name", "users.email", "penghuni.*", "bilik.bilik_id", "bilik.no_bilik", "bilik.tipe_hunian", "properti.jenis_properti", "properti.alamat", "pembayaran.*")
                ->where("pembayaran.pembayaran_id", "=", $pembayaran_id)
                ->get();
        return view('pages.penghuni.rejectedPayment', ['id' => $id, 'bilik_id' => $bilik_id, 'pembayaran_id' => $pembayaran_id, 'dataPembayaranPenghuni' => $dataPembayaranPenghuni]);
    }

    function editRejectedPayment($id, $bilik_id, $pembayaran_id)
    {
        $dataPembayaranBilik =
            DB::table("properti")
                ->join("bilik", function ($join) {
                    $join->on("properti.properti_id", "=", "bilik.properti_id");
                })
                ->select("properti.jenis_properti", "bilik.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->get();

        $dataPembayaranPenghuni =
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
                ->select("users.name", "users.email", "penghuni.*", "bilik.bilik_id", "bilik.no_bilik", "bilik.tipe_hunian", "properti.jenis_properti", "properti.alamat", "pembayaran.*")
                ->where("pembayaran.pembayaran_id", "=", $pembayaran_id)
                ->get();
        return view('pages.penghuni.editRejectedPayment', ['id' => $id, 'bilik_id' => $bilik_id, 'pembayaran_id' => $pembayaran_id, 'dataPembayaranPenghuni' => $dataPembayaranPenghuni, 'dataPembayaranBilik' => $dataPembayaranBilik]);
    }

    function storeEditedPayment(Request $request, $id, $bilik_id, $pembayaran_id)
    {
        $this->validate($request, [
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $dataEditedPayment = Pembayaran::findOrFail($pembayaran_id);
        if ($request->hasFile('bukti_pembayaran')) {
            $image_path = $request->file('bukti_pembayaran')->store('image', 'public');
            $dataEditedPayment->bukti_pembayaran = $image_path;
        }
        $dataEditedPayment->bulan_start_terbayar = $request->bulan_start_terbayar;
        $dataEditedPayment->bulan_end_terbayar = $request->bulan_end_terbayar;
        $dataEditedPayment->tgl_pembayaran = $request->tgl_pembayaran;
        $dataEditedPayment->stat_pembayaran = "Diproses";
        $dataEditedPayment->save();

        $dataBilik = Bilik::findOrFail($bilik_id);
        $dataBilik->status_pembayaran = "Diproses";
        $dataBilik->status_hunian = "Diproses";
        $dataBilik->save();

        return redirect()->route('rpenghuni', ['id' => Auth::user()->id]);
    }
}
