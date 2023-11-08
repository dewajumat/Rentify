<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminKeuanganController extends Controller
{
    function detailPembayaran($id, $bilik_id, $pembayaran_id)
    {
        $detailPembayaranPenghuni =
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
                ->select("bilik.*", "pembayaran.*", "properti.jenis_properti", "properti.alamat")
                ->where("pembayaran.pembayaran_id", "=", $pembayaran_id)
                ->first();
        return view('pages.admin.detailPembayaran', compact('detailPembayaranPenghuni'), ['id' => Auth::user()->id]);
    }

    function keuanganAdmin($id)
    {
        $dataKeuanganAdmin =

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
                ->select("users.name", "pembayaran.*", "bilik.*", "properti.jenis_properti")
                ->where("admin.admin_id", "=", $id)
                ->get();

        return view('pages.admin.indexKeuangan', compact('dataKeuanganAdmin'), ['id' => Auth::user()->id]);
    }
    function generateReport(Request $request, $id)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Perform the necessary query to retrieve the data within the date range
        $generatedReport = DB::table("users")
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
            ->select("users.name", "pembayaran.*", "bilik.*", "properti.*")
            ->where("admin.admin_id", "=", $id)
            ->whereBetween('pembayaran.tgl_pembayaran', [$startDate, $endDate])
            ->get();

        $dataKeuanganPemeliharaan =
            DB::table("bilik")
                ->join("properti", function ($join) {
                    $join->on("bilik.properti_id", "=", "properti.properti_id");
                })
                ->join("pemeliharaan", function ($join) {
                    $join->on("bilik.bilik_id", "=", "pemeliharaan.bilik_id");
                })
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->select("bilik.*", "properti.jenis_properti", "properti.alamat", "pemeliharaan.*")
                ->where("admin.admin_id", "=", $id)
                ->get();

        $dataKeuanganAdmin =

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
                ->select("users.name", "pembayaran.*", "bilik.*", "properti.jenis_properti")
                ->where("admin.admin_id", "=", $id)
                ->get();

        return view('pages.admin.reportPreview', compact('generatedReport', 'dataKeuanganAdmin', 'dataKeuanganPemeliharaan', 'startDate', 'endDate'), ['id' => Auth::user()->id]);
    }
}