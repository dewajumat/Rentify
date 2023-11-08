@extends('layouts.default')
@section('content')
    {{-- <div class="container">
        <div class="container mt-2">
            <div class="card bg-light">
                <div class="card-header text-center">Detail Hunian</div>
                <div class="card-body my-1 mx-4">
                    @foreach ($dataPembayaranBilik as $dpb)
                        <div class="row  py-2">
                            <div class="col-sm-6 text-left">Jenis Properti : {{ $dpb->jenis_properti }}</div>
                            <div class="col-sm-6 text-left">Tipe Hunian : {{ $dpb->tipe_hunian }}</div>
                        </div>
                        <div class="row py-2">
                            <div class="col-sm-6 text-left">Nomor Bilik : {{ $dpb->no_bilik }}</div>
                            <div class="col-sm-6 text-left">Total Pembayaran : @currency($dpb->total_pembayaran)</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div> --}}

    <style>
        @media screen and (max-width: 480px) {
            .card {
                width: 25rem;
            }
        }
    </style>

    <div class="container">
        <div class="container mt-2">
            <div class="card bg-light mt-3">
                <div class="card-header text-center">Detail Pembayaran</div>
                <div class="card-body my-1 mx-4">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form
                                action="{{ route('rStoreEditedPayment', ['id' => Auth::user()->id, 'bilik_id' => $bilik_id, 'pembayaran_id' => $pembayaran_id]) }}"
                                method="POST" enctype="multipart/form-data">

                                @csrf
                                {{-- <div class="mb-3">
		
									<label for="tipe_hunian" class="form-label">Tipe Hunian</label>
									<select class="form-select" id="tipe_hunian" name="tipe_hunian" required readonly disabled>
										@foreach ($dataPembayaranBilik as $dpb)
											<option disabled selected value="">Tipe Hunian</option>
											<option value="Perbulan" {{ $dpb->tipe_hunian == 'Perbulan' ? 'selected' : '' }}>
												Perbulan</option>
											<option value="Per6Bulan" {{ $dpb->tipe_hunian == 'Per6Bulan' ? 'selected' : '' }}>
												Per6Bulan
											</option>
											<option value="Pertahun" {{ $dpb->tipe_hunian == 'Pertahun' ? 'selected' : '' }}>
												Pertahun
											</option>
										@endforeach
									</select>
		
									<div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
										option is required.</div>
								</div> --}}
                                @foreach ($dataPembayaranBilik as $dpb)
                                    <div class="mb-3">
                                        <label class="form-label" for="jenis_properti">Jenis Properti</label>
                                        <input class="form-control" id="jenis_properti" type="text" name="jenis_properti"
                                            placeholder="" value="{{ $dpb->jenis_properti }}" disabled />
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
                                        <input class="form-control" id="total_pembayaran" type="text"
                                            name="total_pembayaran" placeholder="" value="@currency($dpb->total_pembayaran)" disabled />
                                    </div>
                                @endforeach
                                @foreach ($dataPembayaranPenghuni as $dpp)
                                    <div class="mb-3">
                                        <label class="form-label" for="bulanDibayarkan">Bulan Dibayarkan</label>
                                        <div class="d-flex">
                                            <input class="form-control me-2" id="datePickerStart" type="date"
                                                name="bulan_start_terbayar" autocomplete="off" placeholder="Tanggal Mulai"
                                                data-sb-validations="required" value="{{ $dpp->bulan_start_terbayar }}"
                                                required />
                                            <span class="input-group-text" id="basic-addon1">s/d</span>
                                            <input class="form-control ms-2" id="datePickerEnd" type="date"
                                                name="bulan_end_terbayar" autocomplete="off" placeholder="Tanggal Akhir"
                                                data-sb-validations="required" value="{{ $dpp->bulan_end_terbayar }}"
                                                required />
                                        </div>
                                        <div class="invalid-feedback" data-sb-feedback="bulanDibayarkan:required">
                                            Bulan
                                            Dibayarkan is required.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="tgl_pembayaran">Tanggal Pembayaran</label>
                                        <input class="form-control" id="tgl_pembayaran" type="date" name="tgl_pembayaran"
                                            placeholder="Tanggal Pembayaran" data-sb-validations="required"
                                            value="{{ $dpp->tgl_pembayaran }}" required />
                                        <div class="invalid-feedback" data-sb-feedback="tgl_pembayaran:required">
                                            Tanggal Pembayaran is required.</div>
                                    </div>
                                    {{-- <div class="mb-3">
									<label class="form-label" for="total_pembayaran">Total Pembayaran</label>
									@foreach ($dataPembayaranBilik as $dpb)
										<input class="form-control" id="" name="total_pembayaran" type="text"
											placeholder="" value="@currency($dpb->total_pembayaran)" required disabled>
									@endforeach
								</div> --}}

                                    <div class="mb-3">
                                        <label class="form-label" for="bukti_pembayaran">Bukti Pembayaran</label>
                                        <input class="form-control" id="bukti_pembayaran" type="file"
                                            name="bukti_pembayaran" placeholder=""
                                            @if (!$dpp->bukti_pembayaran) required @endif />
                                        <span class="">
                                            @isset($dpp->bukti_pembayaran)
                                                <small>
                                                    {{ basename($dpp->bukti_pembayaran) }}
                                                </small>
                                            @endisset
                                        </span>
                                        <div class="invalid-feedback" data-sb-feedback="bukti_pembayaran:required">
                                            Bukti
                                            Pembayaran is required.</div>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn" id="submitButton" type="submit">Simpan</button>
                                    </div>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
        </div>
    </div>
@stop
