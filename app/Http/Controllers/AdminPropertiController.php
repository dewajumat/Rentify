<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Properti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminPropertiController extends Controller
{
    function indexPropertiAdmin($id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $userId = Auth::user()->id;

            $forMessage1 = Admin::find($userId);
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

            $dataProperti =
                DB::table("properti")
                    ->join("admin", function ($join) {
                        $join->on("properti.admin_id", "=", "admin.admin_id");
                    })
                    ->where("admin.admin_id", "=", $userId)
                    ->select("properti.*", 
                    DB::raw("(SELECT COUNT(*) FROM bilik WHERE bilik.properti_id = properti.properti_id AND bilik.isFilled = 'true') as bilik_count")
                )
                    ->get();

            return view('pages.admin.indexProperti', ['dataProperti' => $dataProperti, 'allFieldsNull' => $allFieldsNull, 'id' => Auth::user()->id]);
        }
    }

    function addPropertiAdmin($id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            return view('pages.admin.addProperti', ['id' => Auth::user()->id]);
        }
    }

    function storePropertiAdmin(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $dataProperti = Properti::create([
                'admin_id' => Auth::user()->id,
                'jenis_properti' => $request->input('jenis_properti'),
                'jumlah_bilik' => $request->input('jumlah_bilik'),
                'alamat' => $request->input('alamat'),
            ]);
            return redirect(route('rpradmin', ['id' => Auth::user()->id]));
        }
    }

    function detailProperti($id, $properti_id)
    {
        $dataProperti =
            DB::table("properti")
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->where("properti.properti_id", "=", $properti_id)
                ->where("admin.admin_id", "=", $id)
                ->select("properti.*", DB::raw("(SELECT COUNT(*) FROM bilik WHERE bilik.properti_id = properti.properti_id AND bilik.isFilled = 'true') as bilik_count"))
                ->get();

        $detProp =
            DB::table("properti")
                ->join("bilik", function ($join) {
                    $join->on("properti.properti_id", "=", "bilik.properti_id");
                })
                ->join("penghuni", function ($join) {
                    $join->on("bilik.penghuni_id", "=", "penghuni.penghuni_id");
                })
                ->join("users", function ($join) {
                    $join->on("penghuni.penghuni_id", "=", "users.id");
                })
                ->select("properti.*", "bilik.*", "penghuni.*", "users.name")
                ->where("properti.properti_id", "=", $properti_id)
                ->where("bilik.status_hunian", "!=", 'Dibatalkan')
                ->get();

        return view('pages.admin.detailProperti', ['detProp' => $detProp, 'dataProperti' => $dataProperti, 'id' => Auth::user()->id, 'properti_id' => $properti_id]);
    }

    function updateProperti($id, $properti_id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $dataProperti =

                DB::table("properti")
                    ->select("properti.*")
                    ->where("properti.properti_id", "=", $properti_id)
                    ->get();

            return view('pages.admin.updateProperti', ['id' => Auth::user()->id, 'properti_id' => $properti_id, 'dataProperti' => $dataProperti]);
        }
    }

    function storeUpdateProperti(Request $request, $id, $properti_id)
    {
        $dataProperti = Properti::findOrFail($properti_id);
        $dataProperti->jenis_properti = $request->jenis_properti;
        $dataProperti->alamat = $request->alamat;
        $dataProperti->jumlah_bilik = $request->jumlah_bilik;
        $dataProperti->save();

        return redirect(route('rpradmin', ['id' => Auth::user()->id]));
    }
    function deleteProperti($id, $properti_id)
    {
        $dataProperti = Properti::find($properti_id);
        if ($dataProperti) {
            $dataProperti->delete();
            Session::flash('successMessage', 'Properti berhasil dihapus');
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
