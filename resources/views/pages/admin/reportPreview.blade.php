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

        @media only screen and (max-width: 480px) {
            #printSection1 .table {
                width: 80% !important;
                /* font-size: 8px !important; */
            }

            .card {
                min-width: 25rem
            }

            #printSection1 {
                margin-top: 1rem;
            }

            .printEachBtn {
                display: none !important;
            }

            .printBtn {
                display: inline;
                text-align: center !important;
            }
        }

        @media (min-width: 481px) {
            .printBtn {
                display: none;
            }
        }

        @media print {

            .pagebreak {
                page-break-before: always;
            }

            .printBtn {
                display: none !important;
            }

            .printEachBtn {
                display: none !important;
            }

            #printDiv1 {
                display: none !important;
            }

            #printDiv2 {
                display: none !important;
            }

            .header {
                display: none !important;
            }

            #printSectionWhole .table {
                font-size: 10px;
            }

        }

    </style>
    <div class="container my-lg-5">
        <div class="container mt-lg-5 printSmallFont" id="printSectionWhole">
            <div class="text-center mb-3" id="printDiv3">
                <button type="button" class="btn btn-primary printBtn"
                    onclick="printSectionFull('printSectionWhole', 'printDiv3', 'PrintDiv1', 'PrintDiv2')">Cetak
                    Laporan Keuangan</button>
                {{-- <button type="button" class="btn btn-primary printBtn"
                    onclick="printSection('printSection1', 'printSection2', 'example', 'example2', 'printDiv1', 'printDiv2', 'printDiv3')">Cetak
                    Laporan
                    Keuangan</button> --}}

            </div>
            {{-- START FROM HERE --}}
            <div class="card bg-light mb-3" id="printSection1">
                <div class="card-header fw-bold text-center">Laporan Pembayaran</div>
                <div class="card-body">
                    <div class="container mt-2 text-end">
                        <button type="button" class="btn btn-primary"
                            disabled>{{ Carbon::parse($startDate)->format('F Y') }}
                            -
                            {{ Carbon::parse($endDate)->format('F Y') }}</button>
                    </div>
                    <div class="container my-4 table-responsive">
                        <table id="example"
                            class="table table-striped table-bordered text-center display nowrap compact w-100">
                            <thead>
                                <tr valign="middle">
                                    <th class="text-center">Nama Penghuni</th>
                                    <th class="text-center">Jenis Properti</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Bilik</th>
                                    <th class="text-center">Tanggal Pembayaran</th>
                                    <th class="text-center">Terbilang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($generatedReport as $gr)
                                    <tr>
                                        <td>{{ $gr->name }}</td>
                                        <td>{{ $gr->jenis_properti }}</td>
                                        <td>{{ $gr->alamat }}</td>
                                        <td>{{ $gr->no_bilik }}</td>
                                        <td>{{ Carbon::parse($gr->tgl_pembayaran)->format('d-F-Y') }}</td>
                                        <td>@currency($gr->total_pembayaran)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="">
                                <tr>
                                    <th colspan="5" style="text-align:right">Total:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="text-center mb-0" id="PrintDiv1">
                        <button type="button" class="btn btn-primary printEachBtn"
                            onclick="printSection('printSection1', 'example', 'PrintDiv1')">Cetak
                            Laporan Pembayaran</button>
                    </div>
                </div>
            </div>

            <div class="pagebreak"> </div>


            <div class="card bg-light mb-3" id="printSection2">
                <div class="card-header fw-bold text-center">Laporan Pemeliharaan</div>
                <div class="card-body">
                    <div class="container mt-2 text-end">
                        <button type="button" class="btn btn-primary"
                            disabled>{{ Carbon::parse($startDate)->format('F Y') }}
                            -
                            {{ Carbon::parse($endDate)->format('F Y') }}</button>
                    </div>
                    <div class="container my-4 table-responsive">
                        <table id="example2" class="table table-striped table-bordered text-center display nowrap"
                            style="width:100%">
                            <thead>
                                <tr valign="middle">
                                    <th class="text-center">Jenis Properti</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Bilik</th>
                                    <th class="text-center">Judul</th>
                                    <th class="text-center">Tanggal Pembayaran</th>
                                    <th class="text-center">Terbilang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataKeuanganPemeliharaan as $gr)
                                    <tr>
                                        <td>{{ $gr->jenis_properti }}</td>
                                        <td>{{ $gr->alamat }}</td>
                                        <td>{{ $gr->no_bilik }}</td>
                                        <td>{{ $gr->judul }}</td>
                                        <td>{{ Carbon::parse($gr->tgl_selesai)->format('d-F-Y') }}</td>
                                        <td>@currency($gr->total)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="">
                                <tr>
                                    <th colspan="5" style="text-align:right">Total:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <div class="text-center mb-0" id="PrintDiv2">
                        <button type="button" class="btn btn-primary printEachBtn"
                            onclick="printSection('printSection2', 'example2', 'PrintDiv2')">Cetak
                            Laporan Pemeliharaan</button>
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
                        // scrollX: true,
                        "footerCallback": function(row, data, start, end, display) {
                            let api = this.api();

                            // Remove the formatting to get integer data for summation
                            let intVal = function(i) {
                                return typeof i === 'string' ?
                                    parseInt(i.replace(/[.,]/g, '').replace('Rp ', '')) :
                                    typeof i === 'number' ?
                                    i :
                                    0;
                            };

                            // Total over all pages
                            total = api
                                .column(5)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            pageTotal = api
                                .column(5, {
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Format totals as Indonesian Rupiah without commas and zeroes after the comma
                            let formatter = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });

                            // Update footer
                            api.column(5).footer().innerHTML =
                                formatter.format(pageTotal).replace(/[,]/g, '');
                        },
                    });
                });
            </script>

            <script>
                $(document).ready(function() {
                    $('#example2').DataTable({
                        "dom": 'rtip',
                        "bInfo": false,
                        "paging": false,
                        // scrollX: true,
                        "footerCallback": function(row, data, start, end, display) {
                            let api = this.api();

                            // Remove the formatting to get integer data for summation
                            let intVal = function(i) {
                                return typeof i === 'string' ?
                                    parseInt(i.replace(/[.,]/g, '').replace('Rp ', '')) :
                                    typeof i === 'number' ?
                                    i :
                                    0;
                            };

                            // Total over all pages
                            total = api
                                .column(5)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            pageTotal = api
                                .column(5, {
                                    page: 'current'
                                })
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Format totals as Indonesian Rupiah without commas and zeroes after the comma
                            let formatter = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });

                            // Update footer
                            api.column(5).footer().innerHTML =
                                formatter.format(pageTotal).replace(/[,]/g, '');
                        }
                    });
                });
            </script>

            <script>
                function printSection(sectionId, tableId, printDivId) {
                    document.getElementById(printDivId).style.visibility = 'hidden';
                    var printContents = document.getElementById(sectionId).innerHTML;
                    var originalContents = document.body.innerHTML;
                    var dataTable = $('#' + tableId).DataTable();
                    dataTable.settings()[0].scrollX = false;

                    // Replace the entire content of the body with the content of the specific section
                    document.body.innerHTML = printContents;

                    // Print the specific section
                    window.print();

                    // Restore the original content of the body
                    document.body.innerHTML = originalContents;
                    dataTable.settings()[0].scrollX = true;
                    document.getElementById(printDivId).style.visibility = 'visible';
                }
            </script>

            <script>
                function printSectionFull(sectionId, printDivFirstId, printDivSecId, printDivThrId) {
                    document.getElementById(printDivFirstId).style.visibility = 'hidden';
                    document.getElementById(printDivSecId).style.visibility = 'hidden';
                    document.getElementById(printDivThrId).style.visibility = 'hidden';

                    var printContents = document.getElementById(sectionId).innerHTML;
                    var originalContents = document.body.innerHTML;

                    // Replace the entire content of the body with the content of the specific section
                    document.body.innerHTML = printContents;

                    // Print the specific section
                    window.print();

                    // Restore the original content of the body
                    document.body.innerHTML = originalContents;
                    // dataTable.settings()[0].scrollX = true;
                    document.getElementById(printDivFirstId).style.visibility = 'visible';
                    document.getElementById(printDivSecId).style.visibility = 'visible';
                    document.getElementById(printDivThrId).style.visibility = 'visible';
                }
            </script>
        </div>
    </div>
@stop
