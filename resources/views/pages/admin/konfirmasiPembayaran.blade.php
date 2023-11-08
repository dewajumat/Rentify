@extends('layouts.default')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <style>
        @media screen and (max-width: 480px) {
            .image-container img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                margin-bottom: 15px;
            }

            .card {
                width: 25rem;
            }
        }
    </style>

    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Bukti Pembayaran Penghuni</div>
                <div class="card-body">
                    <form
                        action="{{ route('rSKonPembayaran', ['id' => Auth::user()->id, 'penghuni_id' => $penghuni_id, 'bilik_id' => $bilik_id, 'pembayaran_id' => $pembayaran_id]) }}"
                        method="POST">
                        @csrf
                        @foreach ($dataPenghuni as $dp)
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 image-container my-auto mx-auto">
                                    <img src="{{ asset('image/' . basename($dp->bukti_pembayaran)) }}"
                                        alt="">
                                </div>
                                {{-- colsec --}}

                                <div class="col-lg-6 col-sm-10">
                                    <div class="row">
                                        {{-- Col1 --}}
                                        <div class="col text-start" style="margin-right: 0px; margin-left: 10px">
                                            <p>Nama</p>
                                            <p>Jenis Properti</p>
                                            <p>Alamat</p>
                                            <p>No. Bilik</p>
                                            <p>Tipe Hunian</p>
                                            <p>Bulan Dibayarkan</p>
                                            <p>Tanggal Pembayaran</p>
                                            <p>Keterangan Penolakan</p>
                                        </div>
                                        {{-- Col2 --}}
                                        <div class="col text-start" style="margin-left: -35px">
                                            <p>:&ensp;{{ $dp->name }}</p>
                                            <p>:&ensp;{{ $dp->jenis_properti }}</p>
                                            @php
                                                $words = explode(' ', $dp->alamat);
                                                $alamat = implode(' ', array_slice($words, 0, 3));
                                            @endphp
                                            <p>:&ensp;{{ $alamat }}</p>
                                            <p>:&ensp;{{ $dp->no_bilik }}</p>
                                            <p>:&ensp;{{ $dp->tipe_hunian }}</p>
                                            <p>:&ensp;{{ Carbon::parse($dp->bulan_start_terbayar)->format('d M y') }} -
                                                {{ Carbon::parse($dp->bulan_end_terbayar)->format('d M y') }}</p>
                                            <p>:&ensp;{{ Carbon::parse($dp->tgl_pembayaran)->format('d F Y') }}</p>
                                            <div>
                                                <input type="text" name="ket_penolakan" id=""
                                                    class="form-control form-control-sm @error('ket_penolakan') is-invalid @enderror">
                                                @error('ket_penolakan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        {{-- <a href="#" class="btn btn-primary mt-3">Konfirmasi Pembayaran</a> --}}
                                        <button type="submit" class="btn btn-primary mt-3" name="submitAction"
                                            value="konfirmasi">Konfirmasi
                                        </button>
                                        <button type="submit" class="btn btn-danger mt-3" name="submitAction"
                                            value="tolak">Tolak</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
