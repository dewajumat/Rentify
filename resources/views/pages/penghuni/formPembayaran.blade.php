@extends('layouts.default')
@section('content')
    <style>
        @media screen and (max-width: 480px) {
            .card {
                width: 25rem !important;
            }
        }
    </style>
    <div class="container">
        <div class="container mt-2">
            <div class="card bg-light mt-3 mx-auto" style="width: 30rem">
                <div class="card-header text-center">Detail Pembayaran</div>
                <div class="card-body my-1 mx-2">
                    <div class="row justify-content-center">
                        <div class="col">
                            <form
                                action="{{ route('rSPembayaranPenghuni', ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @foreach ($dataPembayaranBilik as $dpb)
                                <div class="mb-3">
                                    <label class="form-label" for="jenis_properti">Jenis Properti</label>
                                    <input class="form-control" id="jenis_properti" type="text" name="jenis_properti"
                                        placeholder="" value="{{ $dpb->jenis_properti }}" disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="alamat">Alamat</label>
                                    <input class="form-control" id="alamat" type="text" name="alamat"
                                        placeholder="" value="{{ $dpb->alamat }}" disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="no_bilik">Nomor Bilik</label>
                                    <input class="form-control" id="no_bilik" type="text" name="no_bilik"
                                        placeholder="" value="{{ $dpb->no_bilik }}" disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="tipe_hunian">Tipe Hunian</label>
                                    <input class="form-control" id="tipe_hunian" type="text" name="tipe_hunian"
                                        placeholder="" value="{{ $dpb->tipe_hunian }}" disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="total_pembayaran">Nominal Pembayaran</label>
                                    <input class="form-control" id="total_pembayaran" type="text" name="total_pembayaran"
                                        placeholder="" value="@currency($dpb->total_pembayaran)" disabled />
                                </div>
                                @endforeach
                                <div class="mb-3">
                                    <label class="form-label" for="bulanDibayarkan">Bulan Dibayarkan</label>
                                    <div class="d-flex">
                                        <input class="form-control me-2" id="datePickerStart" type="date"
                                            name="bulan_start_terbayar" autocomplete="off" placeholder="Tanggal Mulai"
                                            data-sb-validations="required" required />
                                        <span class="input-group-text" id="basic-addon1">s/d</span>
                                        <input class="form-control ms-2" id="datePickerEnd" type="date"
                                            name="bulan_end_terbayar" autocomplete="off" placeholder="Tanggal Akhir"
                                            data-sb-validations="required" required />
                                    </div>
                                    <div class="invalid-feedback" data-sb-feedback="bulanDibayarkan:required">
                                        Bulan
                                        Dibayarkan is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="tgl_pembayaran">Tanggal Pembayaran</label>
                                    <input class="form-control" id="tgl_pembayaran" type="date" name="tgl_pembayaran"
                                        placeholder="Tanggal Pembayaran" data-sb-validations="required" required />
                                    <div class="invalid-feedback" data-sb-feedback="tgl_pembayaran:required">
                                        Tanggal Pembayaran is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="bukti_pembayaran">Bukti Pembayaran</label>
                                    <input class="form-control" id="bukti_pembayaran" type="file" name="bukti_pembayaran"
                                        placeholder="" data-sb-validations="required" required />
                                    <div class="invalid-feedback" data-sb-feedback="bukti_pembayaran:required">
                                        Bukti
                                        Pembayaran is required.</div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary btn" id="submitButton" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
        </div>
    </div>
@stop
