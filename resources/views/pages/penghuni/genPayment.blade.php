@extends('layouts.default')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        .dataTables_length {
            text-align: left;
        }
        .page-item.active .page-link {
            background-color: #2185D5 !important;
            border: none;
        }
        .page-link {

            color: dark !important;
            border: none;
        }
        @media screen and (max-width: 480px) {
            .card {
                width: 25rem;
            }
        }
        @media print{
            .btnPrint{
                display: none;
            }
            .header {
                display: none !important;
            }
            #printSection .table{
                font-size: 10px;
            }
        }
    </style>
    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            {{-- START FROM HERE --}}
            <div class="card bg-light mb-3" id="printSection">
                <div class="card-header fw-bold text-center">Riwayat Pembayaran</div>
                <div class="card-body">
                    <div class="container mt-2 text-end">
                        <button type="button" class="btn btn-primary" disabled>{{ Carbon::parse($startDate)->format('F Y') }}
                            -
                            {{ Carbon::parse($endDate)->format('F Y') }}</button>
                    </div>
                    <div class="container my-4 table-responsive">
                        <table id="example" class="table table-striped table-bordered text-center display nowrap compact w-100">
                            <thead>
                                <tr valign="middle">
                                    <th class="text-center">Jenis Properti</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Bilik</th>
                                    <th class="text-center">Tipe Hunian</th>
                                    <th class="text-center">Tanggal Pembayaran</th>
                                    <th class="text-center">Terbilang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($generatedPayment as $gr)
                                    <tr>
                                        <td>{{ $gr->jenis_properti }}</td>
                                        <td>{{ $gr->alamat }}</td>
                                        <td>{{ $gr->no_bilik }}</td>
                                        <td>{{ $gr->tipe_hunian }}</td>
                                        <td>{{ Carbon::parse($gr->tgl_pembayaran)->format('d-F-Y') }}</td>
                                        <td>@currency($gr->total_pembayaran)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-4 mb-0 btnPrint" id="PrintDiv">
                        <button type="button" class="btn btn-primary" onclick="printSection('printSection')">Cetak
                            Riwayat Pembayaran</button>
                    </div>
                </div>
            </div>
            {{-- DataTables JS --}}
            <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#example').DataTable({
                        "dom": 'rtip',
                        "bInfo": false,
                        "paging": false,
                    });
                });
            </script>
            <script>
                function printSection(sectionId) {
                    document.getElementById('PrintDiv').style.visibility = 'hidden';
                    var printContents = document.getElementById(sectionId).outerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                    document.getElementById('PrintDiv').style.visibility = 'visible';
                }
            </script>
        </div>
    </div>
@stop
