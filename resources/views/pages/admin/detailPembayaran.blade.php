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
            }

            .detM1 {
                margin-left: 15px !important;
            }

            .detM2 {
                margin-right: 0px;
                margin-left: -35px;
            }

            .card {
                width: 25rem;
            }
        }

        .image-container img {
            max-width: 80% !important;
            max-height: auto !important;
            object-fit: contain;
        }
    </style>

    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Detail Pembayaran</div>
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-6 col-sm-12 image-container my-auto mx-auto">
                            <img src="{{ asset('image/' . basename($detailPembayaranPenghuni->bukti_pembayaran)) }}" alt="">

                        </div>
                        {{-- colsec --}}
                        <div class="col">
                            <div class="row mt-3 mb-2">
                                {{-- Col1 --}}
                                <div class="col text-start detM1" style="margin-left: 30px">
                                    <p>Jenis Properti</p>
                                    <p>Alamat</p>
                                    <p>No. Bilik</p>
                                    <p>Tipe Hunian</p>
                                    <p>Tanggal Pembayaran</p>
                                    <p>Bulan Dibayarkan</p>
                                    <p>Total Pembayaran</p>
                                </div>
                                {{-- Col2 --}}
                                <div class="col text-start detM2">
                                    <p>:&ensp;{{ $detailPembayaranPenghuni->jenis_properti }}</p>
                                    @php
                                        $words = explode(' ', $detailPembayaranPenghuni->alamat);
                                        $alamat = implode(' ', array_slice($words, 0, 3));
                                    @endphp
                                    <p>:&ensp;{{ $alamat }}</p>
                                    <p>:&ensp;{{ $detailPembayaranPenghuni->no_bilik }}</p>
                                    <p>:&ensp;{{ $detailPembayaranPenghuni->tipe_hunian }}</p>
                                    <p>:&ensp;{{ Carbon::parse($detailPembayaranPenghuni->tgl_pembayaran)->format('d F Y') }}</p>
                                    <p>:&ensp;{{ Carbon::parse($detailPembayaranPenghuni->bulan_start_terbayar)->format('d M y') }}
                                        -
                                        {{ Carbon::parse($detailPembayaranPenghuni->bulan_end_terbayar)->format('d M y') }}</p>
                                    <p>:&ensp;@currency($detailPembayaranPenghuni->total_pembayaran)</p>
                                </div>
                            </div>
                            <div>
                                {{-- <a href="#" class="btn btn-primary mt-3">Konfirmasi Pembayaran</a> --}}
                                {{-- <form
                                        action="{{ route('rSKonPembayaran', ['id' => Auth::user()->id, 'penghuni_id' => $penghuni_id, 'bilik_id' => $bilik_id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary mt-3">Konfirmasi Pembayaran</button>
                                    </form> --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
