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
            }

            .detM1 {
                margin-left: 35px;
            }

            .detM2 {
                margin-right: 0px;
                margin-left: 20px;
            }
        }
    </style>


    <div class="container my-lg-3">
        <div class="container mb-3">
            <div class="card bg-light my-auto text-center">
                <div class="card-header">Detail Pemeliharaan</div>
                <div class="card-body">
                    @foreach ($dataPemeliharaanBilik as $dpb)
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-sm-12 image-container my-auto mx-auto">
                                <img src="{{ asset('image/' . basename($dpb->gambar)) }}" alt="">
                            </div>
                            {{-- colsec --}}
                            <div class="card col-lg-6 col-sm-10 mx-auto px-0" style="transform: scale(0.95);">
                                <div class="card-header">
                                    Data Pemeliharaan
                                </div>
                                <div class="row mt-3 mb-2">
                                    {{-- This Col1 --}}
                                    <div class="col-5 text-start detM1" style="margin-left: 30px">
                                        <p>Nama</p>
                                        <p>Jenis Properti</p>
                                        <p>Alamat</p>
                                        <p>No. Bilik</p>
                                        <p>Tanggal Pengajuan</p>
                                        <p>Judul</p>
                                        <p>Deskripsi</p>

                                        @if ($dpb->status_pemeliharaan === 'Ditolak')
                                            <p>Keterangan Penolakan</p>
                                        @elseif ($dpb->status_pemeliharaan === 'Selesai')
                                            <p>Tanggal Selesai</p>
                                            <p>Total Pembayaran</p>
                                        @endif

                                    </div>
                                    {{-- Col2 --}}
                                    <div class="col text-start detM2">
                                        <p row-span>:&ensp;{{ $dpb->name }}</p>
                                        <p row-span>:&ensp;{{ $dpb->jenis_properti }}</p>
                                        @php
                                            $words = explode(' ', $dpb->alamat);
                                            $alamat = implode(' ', array_slice($words, 0, 3));
                                        @endphp
                                        <p row-span>:&ensp;{{ $alamat }}</p>
                                        <p row-span>:&ensp;{{ $dpb->no_bilik }}</p>
                                        <p row-span>:&ensp;{{ Carbon::parse($dpb->tgl_pengajuan)->format('d-F-Y') }}</p>
                                        <p row-span>:&ensp;{{ $dpb->judul }}</p>
                                        <p row-span>:&ensp;{{ $dpb->deskripsi }}</p>

                                        @if ($dpb->status_pemeliharaan === 'Ditolak')
                                            <div>
                                                <p>:&ensp;{{ $dpb->ket_penolakan }}</p>
                                            </div>
                                        @elseif ($dpb->status_pemeliharaan === 'Selesai')
                                            <p>:&ensp;{{ Carbon::parse($dpb->tgl_selesai)->format('d-F-Y') }}</p>
                                            <p>:&ensp;@currency($dpb->total)</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Rupiah Input --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stop
