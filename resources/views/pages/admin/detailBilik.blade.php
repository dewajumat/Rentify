@extends('layouts.default')
@section('content')

    @php
        use Carbon\Carbon;
        use Illuminate\Support\Facades\Storage;
    @endphp

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        .page-item.active .page-link {
            background-color: #2185D5 !important;
            border: none;
        }

        .page-link {
            color: black !important;
            border: none;
        }

        .image-container {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 75%;
            max-height: 75vh;
        }

        .image-container img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        @media screen and (max-width: 480px) {
            .card {
                width: 25rem
            }
        }

        .table thead th {
            vertical-align: middle;
        }

        .table tbody tr {
            vertical-align: middle;
        }

        .table tbody tr {
            text-align: center;
        }

        .dataTables_length {
            text-align: left;
        }

        table.dataTable {
            padding-top: 5px;
        }

        td.child {
            text-align: left;
        }

        span.dtr-title {
            min-width: 160px !important;
        }
    </style>

    <div class="container">
        <div class="container mt-2">
            <div class="card bg-light mb-3 text-left">
                <div class="card-header text-center fw-bold">Detail Penghuni</div>
                <div class="card-body my-1 mx-4 ">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <div class="image-container">
                                    <img src="{{ asset('image/' . basename($dataBilik->foto)) }}" alt="" style="">
                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="text-left mt-2">Nama : {{ $dataBilik->name }}</div>
                                <div class="text-left mt-3">Jenis Kelamin : {{ $dataBilik->jenis_kelamin }}</div>
                                <div class="text-left mt-3">Email : {{ $dataBilik->email }}</div>
                                <div class="text-left mt-3">No. Handphone : {{ $dataBilik->no_handphone }}</div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="text-left mt-3">Kartu Tanda Penduduk : <a href="#" style="color: black" data-bs-toggle="modal"
                                    data-bs-target="#nikModal">Lihat KTP</a></div>
                                <div class="text-left mt-3">Kartu Keluarga : <a href="#" style="color: black" data-bs-toggle="modal"
                                    data-bs-target="#kkModal">Lihat Kartu Keluarga</a></div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header fw-bold">Data Pembayaran Bilik</div>
                <div class="card-body">
                    <div class="">
                        <div class="container my-2">
                            <table id="example" class="table table-striped table-bordered display nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Jenis Properti</th>
                                        <th class="text-center">Alamat</th>
                                        <th class="text-center">No. Bilik</th>
                                        <th class="text-center">Tipe Hunian</th>
                                        <th class="text-center">Tanggal Pembayaran</th>
                                        <th class="text-center">Periode Terbayar</th>
                                        <th class="text-center">Terbilang</th>
                                        <th class="text-center">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataPembayaranBilik as $dpb)
                                        @if ($dpb->status_pembayaran == 'Terbayar')
                                            <tr>
                                                <td>{{ $dpb->jenis_properti }}</td>
                                                @php
                                                    $words = explode(' ', $dpb->alamat);
                                                    $alamat = implode(' ', array_slice($words, 0, 3));
                                                @endphp
                                                <td>{{ $alamat }}</td>
                                                <td>{{ $dpb->no_bilik }}</td>
                                                <td>{{ $dpb->tipe_hunian }}</td>
                                                <td>{{ Carbon::parse($dpb->tgl_pembayaran)->format('d-F-Y') }}</td>
                                                <td>{{ Carbon::parse($dpb->bulan_start_terbayar)->format('d M Y') }} -
                                                    {{ Carbon::parse($dpb->bulan_end_terbayar)->format('d M Y') }}</td>
                                                <td>@currency($dpb->total_pembayaran)</td>
                                                <td><a
                                                    href="{{ route('rDetPembayaranAdmin', ['id' => Auth::user()->id, 'bilik_id' => $dataBilik->bilik_id, 'pembayaran_id' => $dpb->pembayaran_id]) }}"><i
                                                        class="fa-solid fa-image"></i></a></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Modal for displaying the NIK image -->
                            <div class="modal fade" id="nikModal" tabindex="-1" aria-labelledby="nikModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="nikModalLabel">Foto Profile</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="{{ asset('image/' . basename($dataBilik->nik)) }}"
                                                class="img-fluid mx-auto d-block" alt="NIK Image">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for displaying the NIK image -->
                            <div class="modal fade" id="kkModal" tabindex="-1" aria-labelledby="kkModal"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="kkModal">Kartu Tanda Penduduk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="{{ asset('image/' . basename($dataBilik->no_kk)) }}"
                                                class="img-fluid mx-auto d-block" alt="NIK Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "bInfo": false,
                "responsive": true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: 2
                    },
                ]
            });
        });
    </script>
@stop
