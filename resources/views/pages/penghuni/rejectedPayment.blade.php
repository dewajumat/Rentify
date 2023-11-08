@extends('layouts.default')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    {{-- <div class="text-center">
		<h1 class="">Halaman konfirmasi pembayaran dengan id admin {{ $id }}</h1>
		@foreach ($dataPenghuni as $dp)
			<h4>Dengan nama penghuni {{ $dp->name }}</h4>
			<h5>ID : {{ $penghuni_id }}</h5>
		@endforeach
	</div> --}}

    <style>
        @media screen and (max-width: 480px) {
            .image-container img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                margin-bottom: 15px;
            }

            .card{
                width: 25rem;
            }
        }
    </style>

    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Bukti Pembayaran Penghuni</div>
                <div class="card-body">
                    @foreach ($dataPembayaranPenghuni as $dpp)
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-sm-12 image-container my-auto mx-auto">
                                <img src="{{ asset('image/' . basename($dpp->bukti_pembayaran)) }}" alt="">
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
                                    <div class="col text-start" style="margin-left: -45px">
                                        <p>:&ensp;{{ $dpp->name }}</p>
                                        <p>:&ensp;{{ $dpp->jenis_properti }}</p>
                                        @php
                                            $words = explode(' ', $dpp->alamat);
                                            $alamat = implode(' ', array_slice($words, 0, 3));
                                        @endphp
                                        <p>:&ensp;{{ $alamat }}</p>
                                        <p>:&ensp;{{ $dpp->no_bilik }}</p>
                                        <p>:&ensp;{{ $dpp->tipe_hunian }}</p>
                                        <p>:&ensp;{{ Carbon::parse($dpp->bulan_start_terbayar)->format('d M Y') }} -
                                            {{ Carbon::parse($dpp->bulan_end_terbayar)->format('d M Y') }}</p>
                                        <p>:&ensp;{{ Carbon::parse($dpp->tgl_pembayaran)->format('d F Y') }}</p>
                                        <div>
                                            <input type="text" name="ket_penolakan" id=""
                                                class="form-control form-control-sm" value="{{ $dpp->ket_penolakan }}"
                                                disabled>
                                            @error('ket_penolakan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('rEditRejectedPayment', ['id' => Auth::user()->id, 'bilik_id' => $bilik_id, 'pembayaran_id' => $pembayaran_id]) }}"
                                        class="btn btn-primary mt-3">Edit Pembayaran</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
