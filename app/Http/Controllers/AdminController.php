<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bilik;
use App\Models\Pembayaran;
use App\Models\Pemeliharaan;
use App\Models\Properti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
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

            $calonPenghuni =
                DB::table("users")
                    ->join("penghuni", function ($join) {
                        $join->on("users.id", "=", "penghuni.penghuni_id");
                    })
                    ->select("users.name", "users.email", "penghuni.penghuni_id")
                    ->where("penghuni.penghuni_id", "=", $penghuniId)
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

            return view('pages.admin.addPenghuni1', ['namaCalonPenghuni' => $namaCalonPenghuni, 'calonPenghuni' => $calonPenghuni, 'penghuniId' => $penghuniId, 'dataProperti' => $dataProperti, 'dataBilik' => $dataBilik, 'id' => Auth::user()->id]);
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
                'isFilled' => 'false',
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

    //Profile Admin

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
        $profileAdmin->nik = $request->nik;
        $profileAdmin->jenis_kelamin = $request->jenis_kelamin;
        $profileAdmin->no_handphone = $request->no_handphone;

        if ($request->hasFile('foto')) {
            // User uploaded a new photo
            $this->validate($request, [
                'foto' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);

            // Store the new photo
            $image_path = $request->file('foto')->store('image', 'public');
            $profileAdmin->foto = $image_path;
        } elseif (!$request->hasFile('foto')) {

        }
        $profileAdmin->save();

        $userData = User::findOrFail($id);
        $userData->name = $request->name;
        $userData->email = $request->email;
        $userData->save();

        return redirect()->route('radmin', ['id' => Auth::user()->id]);
    }


    // Properti

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
                    ->get();

            // $dataBilik =
            //     DB::table("bilik")
            //         ->join("properti", function ($join) {
            //             $join->on("bilik.properti_id", "=", "properti.properti_id");
            //         })
            //         ->join("admin", function ($join) {
            //             $join->on("properti.admin_id", "=", "admin.admin_id");
            //         })
            //         ->select("bilik.*", "properti.*")
            //         ->where("admin.admin_id", "=", $userId)
            //         ->get();


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

    // Pembayaran penghuni
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
            // $dataBilik->isFilled = true;
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

    // Pemeliharaan
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

    // Keuangan Admin

    function keuanganAdmin($id)
    {
        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });
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

    function deleteBilikPenghuni($id, $bilik_id)
    {
        $dataBilik = Bilik::find($bilik_id);
        if ($dataBilik) {
            $dataBilik->delete();
            Session::flash('successMessage', 'Data bilik berhasil dihapus');
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
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

    function detailBilik($id, $bilik_id)
    {

        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });

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
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->select("users.name", "pembayaran.*", "bilik.*", "properti.jenis_properti", "properti.alamat")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->where("admin.admin_id", "=", $id)
                ->get();

        return view('pages.admin.detailBilik', compact('dataBilik', 'dataPembayaranBilik'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);

    }

    function updateBilikPenghuni($id, $penghuni_id, $bilik_id)
    {

        dd($penghuni_id);
        $dataBilikPenghuni =
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
                ->join("pembayaran", function ($join) {
                    $join->on("bilik.bilik_id", "=", "pembayaran.bilik_id");
                })
                ->join("admin", function ($join) {
                    $join->on("properti.admin_id", "=", "admin.admin_id");
                })
                ->select("users.name", "penghuni.*", "bilik.*", "properti.*", "pembayaran.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->where("admin.admin_id", "=", $id)
                ->get();

        return view('pages.admin.updateBilik', compact('dataBilikPenghuni'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);


    }

    function storeUpdateBilikPenghuni(Request $request, $id, $bilik_id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $tp = $request->input('total_pembayaran');
            $cleanhtp = str_replace(['Rp', '.', ','], '', $tp);
            $total_pembayaran_clean = (int) $cleanhtp;

            $penghuniId = $request->input('dropdown');

            $bilikData = Bilik::create([
                'penghuni_id' => $penghuniId,
                'properti_id' => $request->properti_id,
                'no_bilik' => $request->jumlah_bilik,
                'status_hunian' => "Perlu Konfirmasi",
            ]);
            $dataPembayaran = Pembayaran::create([
                'bilik_id' => $bilikData->bilik_id,
                'penghuni_id' => $penghuniId,
                'total_pembayaran' => $total_pembayaran_clean,
                'tipe_hunian' => $request->tipe_hunian,
            ]);
            return redirect()->route('radmin', $id);
        }
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

        // You can pass the retrieved data to a view or perform any other operations as needed
        return view('pages.admin.reportPreview', compact('generatedReport', 'dataKeuanganAdmin', 'dataKeuanganPemeliharaan', 'startDate', 'endDate'), ['id' => Auth::user()->id]);
        // return view('your-report-view')->with('dataKeuangan', $dataKeuangan);
    }
}