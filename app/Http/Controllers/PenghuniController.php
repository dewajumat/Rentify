<?php

namespace App\Http\Controllers;

use App\Models\Bilik;
use App\Models\Pembayaran;
use App\Models\Pemeliharaan;
use App\Models\Penghuni;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class PenghuniController extends Controller
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

            // dd($dataPenghuni);
            return view('pages.penghuni.indexProfilePenghuni', compact('dataPenghuni'), ['id' => Auth::user()->id]);

            // dd($dataPenghuni);




            // $results = Penghuni::where('penghuni_id', $userId)
            //     ->where(function ($query) {
            //         $query->whereNull('jenis_kelamin')
            //             ->orWhereNull('no_handphone')
            //             ->orWhereNull('nik')
            //             ->orWhereNull('no_kk');
            //     })
            //     ->get();



            // if ($results->isEmpty()) {
            //     // None of the columns are null for the authorized user
            //     return view('penghuni.formMenghuni2', ['id' => Auth::user()->id]);
            // } else {
            //     // At least one of the columns is null for the authorized user
            //     $message = 'Harap lengkapi profil anda';
            //     return view('penghuni.formMenghuni1', ['results' => $results, 'datapenghuni' => $dataPenghuni, 'message' => $message, 'id' => Auth::user()->id]);
            // }
        } else {
            abort(403, 'Unauthorized');
        }
    }

    function detailBilikPenghuni($id, $bilik_id)
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
                ->select("bilik.*", "properti.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->where("penghuni.penghuni_id", "=", $id)
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
        $penghuniData->nik = $request->nik;
        $penghuniData->no_kk = $request->no_kk;
        $penghuniData->jenis_kelamin = $request->jenis_kelamin;
        $penghuniData->no_handphone = $request->no_handphone;

        if ($request->hasFile('foto')) {
            // User uploaded a new photo
            $this->validate($request, [
                'foto' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);

            // Store the new photo
            $image_path = $request->file('foto')->store('image', 'public');
            $penghuniData->foto = $image_path;
        } elseif (!$request->hasFile('foto')) {

        }
        $penghuniData->save();

        $userData = User::findOrFail($id);
        $userData->name = $request->name;
        $userData->email = $request->email;
        $userData->save();

        return redirect()->route('rpenghuni', ['id' => Auth::user()->id]);
    }
    function indexPenghuniId($id)
    {
        if (Auth::check() && Auth::user()->id == $id) {
            $authId = Auth::user()->id;

            Blade::directive('currency', function ($expression) {
                return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
            });

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

            // No action needed if $statusHunian is 'aktif'

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
                ->select("bilik.no_bilik", "properti.properti_id", "properti.jenis_properti", "bilik.*")
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
            $dataBilik->save();

        } elseif ($submit === 'cancelButton') {
            $request->validate([
                'ket_pembatalan' => 'required'
            ], [
                'ket_pembatalan.required' => 'Keterangan pembatalan wajib diisi',
            ]);
            $dataBilik = Bilik::findOrFail($bilik_id);
            $dataBilik->status_hunian = "Dibatalkan";
            $dataBilik->ket_pembatalan = $request->ket_pembatalan;
            $dataBilik->save();
        }
        return redirect()->route('rpenghuni', ['id' => Auth::user()->id]);





        // $dataBilik = Bilik::findOrFail($bilik_id);
        // $dataBilik->status_hunian = "Pending";
        // $dataBilik->status_pembayaran = "Belum bayar";
        // $dataBilik->save();

        // return redirect()->route('rpenghuni', ['id' => Auth::user()->id]);

    }

    function formPembayaran($id, $bilik_id)
    {
        // dd($id);
        $authId = Auth::user()->id;
        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });
        // $dataPembayaran =

        //     DB::table("pembayaran")
        //         ->join("bilik", function ($join) {
        //             $join->on("pembayaran.bilik_id", "=", "bilik.bilik_id");
        //         })
        //         ->join("penghuni", function ($join) {
        //             $join->on("bilik.penghuni_id", "=", "penghuni.penghuni_id");
        //         })
        //         ->select("pembayaran.*")
        //         ->where("penghuni.penghuni_id", "=", $authId)
        //         ->get();

        // $pembayaranIds = $dataPembayaran->pluck('pembayaran_id');

        $dataPembayaranBilik =
            DB::table("properti")
                ->join("bilik", function ($join) {
                    $join->on("properti.properti_id", "=", "bilik.properti_id");
                })
                ->select("properti.jenis_properti", "bilik.*")
                ->where("bilik.bilik_id", "=", $bilik_id)
                ->get();





        // return view('pages.penghuni.formPembayaran', compact('dataPembayaran', 'pembayaranIds'), ['id' => Auth::user()->id]);
        return view('pages.penghuni.formPembayaran', compact('dataPembayaranBilik'), ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]);
    }

    function sFormPembayaran(Request $request, $id, $bilik_id)
    {
        $this->validate($request, [
            'bukti_pembayaran' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $image_path = $request->file('bukti_pembayaran')->store('image', 'public');


        // $dataPembayaran = Pembayaran::where('bilik_id', $bilik_id)->firstOrFail();
        // $dataPembayaran->bukti_pembayaran = $image_path;
        // $dataPembayaran->bulan_start_terbayar = $request->bulan_start_terbayar;
        // $dataPembayaran->bulan_end_terbayar = $request->bulan_end_terbayar;
        // $dataPembayaran->tgl_pembayaran = $request->tgl_pembayaran;
        // $dataPembayaran->status_pembayaran = "Diproses";
        // $dataPembayaran->save();

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
        // dd($pembayaran_id);

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

        return view('pages.penghuni.detailPemeliharaan', ['id' => Auth::user()->id, 'dataPemeliharaanBilik' => $dataPemeliharaanBilik, 'pemeliharaan_id' => $pemeliharaan_id]);
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