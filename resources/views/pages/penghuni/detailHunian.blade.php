@extends('layouts.default')
@section('content')
    @php
        use Carbon\Carbon;
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
                width: 25rem;
            }
        }

        .dataTables_length {
            text-align: left;
        }

        table.dataTable {
            padding-top: 5px;
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

        td.child {
            text-align: left;
        }

        td .child {
            text-align: left;
        }

        span.dtr-title {
            min-width: 145px !important;
        }
    </style>
    <div class="container">
        <div class="container mt-2">
            <div class="card bg-light mb-3 text-left">
                <div class="card-header text-center fw-bold">Detail Hunian</div>
                <div class="card-body mx-0 ">
                    @foreach ($dataBilik as $db)
                        <div class="container-fluid">
                            <div class="row">
                                <!-- Column 1 and Column 2 -->
                                <div class="col-md-2 col-6 fw-bold" style="color: #373737">
                                    <p>Pemilik Properti</p>
                                    <p>Jenis Properti</p>
                                    <p>Alamat</p>
                                </div>
                                <div class="col-md-3 col-6">
                                    <p>: {{ $db->name }}</p>
                                    <p>: {{ $db->jenis_properti }}</p>
                                    <p>: {{ implode(' ', array_slice(explode(' ', $db->alamat), 0, 3)) }}</p>
                                </div>
                                <!-- Column 3 and Column 4 -->
                                <div class="col-md-2 col-6 fw-bold" style="color: #373737">
                                    <p>No. Bilik</p>
                                    <p>Tipe Hunian</p>
                                    <p>Total Pembayaran</p>
                                </div>
                                <div class="col-md-5 col-6">
                                    <p>: {{ $db->no_bilik }}</p>
                                    <p>: {{ $db->tipe_hunian }}</p>
                                    <p>: @currency($db->total_pembayaran)</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header fw-bold">Data Pembayaran Hunian</div>
                <div class="card-body">
                    <div class="">
                        <div class="container my-2">
                            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Tanggal Pembayaran</th>
                                        <th class="text-center">Periode Terbayar</th>
                                        <th class="text-center">Terbilang</th>
                                        <th class="text-center">Bukti Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataPembayaranBilik as $dpb)
                                        @if ($dpb->status_pembayaran == 'Terbayar')
                                            <tr>
                                                <td>{{ Carbon::parse($dpb->tgl_pembayaran)->format('d-F-Y') }}</td>
                                                <td>{{ Carbon::parse($dpb->bulan_start_terbayar)->format('d M Y') }} -
                                                    {{ Carbon::parse($dpb->bulan_end_terbayar)->format('d M Y') }}</td>
                                                <td>@currency($dpb->total_pembayaran)</td>
                                                <td><a
                                                        href="{{ route('rDetPembayaranPenghuni', ['id' => Auth::user()->id, 'pembayaran_id' => $dpb->pembayaran_id]) }}"><i
                                                            class="fa-solid fa-image"></i></a></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
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
                        targets: -1
                    },
                ]
            });
        });
    </script>
@stop
