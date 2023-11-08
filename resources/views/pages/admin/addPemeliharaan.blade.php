@extends('layouts.default')
@section('content')
    <style>
        @media screen and (max-width: 480px) {
            .image-container img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }

            .detM1{
                margin-left: 20px;
            }

            .detM2{
                margin-right: 10px; 
                margin-left: 0px;
            }
        }
    </style>


    <div class="container my-lg-3">
        <div class="container mb-3">
            <div class="card bg-light my-auto text-center">
                <div class="card-header">Request Pemeliharaan</div>
                <div class="card-body">
                    <form
                        action="{{ route('rsmadmin', ['id' => Auth::user()->id, 'penghuni_id' => $penghuni_id, 'bilik_id' => $bilik_id, 'pemeliharaan_id' => $pemeliharaan_id]) }}"
                        method="POST">
                        @csrf
                        @foreach ($dataPemeliharaanBilik as $dpb)
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 image-container my-auto mx-auto">
                                    <img src="{{ asset('image/'.basename($dpb->gambar)) }}" alt="">
                                </div>
                                {{-- colsec --}}
                                <div class="card col-lg-6 col-sm-10 mx-auto px-0" style="transform: scale(0.95);">
                                    <div class="card-header">
                                        Detail Pemeliharaan
                                    </div>
                                    <div class="row mt-3 mb-2">
                                        {{-- This Col1 --}}
                                        <div class="col text-start detM1" style="margin-left: 10px;" >
                                            <p>Nama</p>
                                            <p>Jenis Properti</p>
                                            <p>Alamat</p>
                                            <p>No. Bilik</p>
                                            <p>Tanggal Pengajuan</p>
                                            <p>Judul</p>
                                            <p>Deskripsi</p>                                            

                                            @if ($dpb->status_pemeliharaan === 'Pending')
                                                <p>Keterangan Penolakan</p>
                                            @elseif ($dpb->status_pemeliharaan === 'Diproses')
                                                <p>Tanggal Selesai</p>
                                                <p>Total Pembayaran</p>
                                            @endif

                                        </div>
                                        {{-- Col2 --}}
                                        <div class="col text-start detM2" style=" margin-right:10px">
                                            <p>:&ensp;{{ $dpb->name }}</p>
                                            <p>:&ensp;{{ $dpb->jenis_properti }}</p>
                                            @php
                                            $words = explode(' ', $dpb->alamat);
                                            $alamat = implode(' ', array_slice($words, 0, 3));
                                            @endphp
                                            <p>:&ensp;{{ $alamat }}</p>
                                            <p>:&ensp;{{ $dpb->no_bilik }}</p>
                                            <p>:&ensp;{{ $dpb->tgl_pengajuan }}</p>
                                            <p>:&ensp;{{ $dpb->judul }}</p>
                                            <p>:&ensp;{{ $dpb->deskripsi }}</p>
                                            
                                            @if ($dpb->status_pemeliharaan === 'Pending')
                                                <div>
                                                    <input class="form-control @error('ket_penolakan') is-invalid @enderror"
                                                        id="ket_penolakan" name="ket_penolakan" type="text"
                                                        placeholder="" value="">
                                                    @error('ket_penolakan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @elseif ($dpb->status_pemeliharaan === 'Diproses')
                                                <input class="form-control pt-0 mb-1" id="tgl_pembayaran" type="date"
                                                    name="tgl_selesai" placeholder="Tanggal Pembayaran"
                                                    data-sb-validations="required" required />
                                                <input class="form-control pt-0" id="rupiah" name="total"
                                                    type="text" placeholder="" value="" required>
                                            @endif


                                        </div>
                                    </div>
                                    <div class="mb-3 d-flex align-items-center justify-content-center">
                                        @if ($dpb->status_pemeliharaan === 'Pending')
                                            <button type="submit" name="submitAction" value="konfirmasi"
                                                class="btn btn-primary">Konfirmasi</button>
                                            &nbsp;
                                            &nbsp;
                                            &nbsp;
                                            <button type="submit" name="submitAction" value="tolak"
                                                class="btn btn-danger">Tolak</button>
                                        @elseif ($dpb->status_pemeliharaan === 'Diproses')
                                            <button type="submit" name="submitAction" value="dataBayar"
                                                class="btn btn-primary">Selesai</button>
                                        @endif

                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Rupiah Input --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/rupiah-input.js') }}"></script>
@stop
