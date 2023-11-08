<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class PenghuniKeuanganController extends Controller
{
    function historyPembayaran($id)
    {
        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });
        $dataPembayaranPenghuni =
            DB::table("bilik")
                ->join("pembayaran", function ($join) {
                    $join->on("bilik.bilik_id", "=", "pembayaran.bilik_id");
                })
                ->join("properti", function ($join) {
                    $join->on("bilik.properti_id", "=", "properti.properti_id");
                })
                ->join("penghuni", function ($join) {
                    $join->on("bilik.penghuni_id", "=", "penghuni.penghuni_id");
                })
                ->select("pembayaran.*", "bilik.*", "properti.jenis_properti", "properti.alamat")
                ->where("penghuni.penghuni_id", "=", $id)
                ->get();

        return view('pages.penghuni.historyPembayaran', compact('dataPembayaranPenghuni'), ['id' => Auth::user()->id]);
    }

    function detailPembayaran($id, $pembayaran_id)
    {
        $detailPembayaran =

            DB::table("bilik")
                ->join("pembayaran", function ($join) {
                    $join->on("bilik.bilik_id", "=", "pembayaran.bilik_id");
                })
                ->join("properti", function ($join) {
                    $join->on("bilik.properti_id", "=", "properti.properti_id");
                })
                ->join("penghuni", function ($join) {
                    $join->on("bilik.penghuni_id", "=", "penghuni.penghuni_id");
                })
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->join("users", function ($join) {
                    $join->on("users.id", "=", "admin.admin_id");
                })
                ->select("users.name","bilik.*", "pembayaran.*", "properti.jenis_properti", "properti.alamat")
                ->where("pembayaran.pembayaran_id", "=", $pembayaran_id)
                ->first();

        return view('pages.penghuni.detailPembayaran', compact('detailPembayaran'), ['id' => Auth::user()->id, $pembayaran_id]);

    }

    function generatePayment(Request $request, $id)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $generatedPayment = DB::table("users")
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
            ->select("penghuni.*", "pembayaran.*", "bilik.*", "properti.*")
            ->where("penghuni.penghuni_id", "=", $id)
            ->whereBetween('pembayaran.tgl_pembayaran', [$startDate, $endDate])
            ->get();

        return view('pages.penghuni.genPayment',compact('generatedPayment', 'startDate', 'endDate'), ['id' => $id]);
    }
}
