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

    div.dataTables_scroll {
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

    @media screen and (max-width: 480px) {
        .btn-add {
            text-align: center !important;
        }

        .card {
            width: 25rem
        }
    }

    @media print and (max-width: 480px){
        .table{
            font-size: 8px;
        }
    }

    span.dtr-title {
        min-width: 155px !important;
    }
</style>
    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 " id="printPreviewContainer">
                <div class="card-header fw-bold text-center">Data Keuangan</div>
                <div class="card-body">
                    <div class="container my-4">
                        <div class="container mt-2">
                            <form action="{{ route('rGenReport', ['id' => $id]) }}">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="d-flex">
                                        <input class="form-control me-2" id="startDate" type="date"
                                            name="startDate" autocomplete="off" placeholder=""
                                            data-sb-validations="required" required />
                                        <span class="input-group-text" id="basic-addon1">s/d</span>
                                        <input class="form-control ms-2" id="endDate" type="date"
                                            name="endDate" autocomplete="off" placeholder=""
                                            data-sb-validations="required" required />
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary mt-2 mb-2" type="submit">
                                        Buat Laporan Keuangan
                                    </button>
                                </div>
                            </form>
                        </div>
                        <table id="example" class="table table-striped table-bordered display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" >Nama Penghuni</th>
                                    <th class="text-center" >Jenis Properti</th>
                                    <th class="text-center" >No. Bilik</th>
                                    <th class="text-center" >Tipe Hunian</th>
                                    <th class="text-center" >Tanggal Pembayaran</th>
                                    <th class="text-center" >Terbilang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataKeuanganAdmin as $dka)
                                    <tr>
                                        <td>{{ $dka->name }}</td>
                                        <td>{{ $dka->jenis_properti }}</td>
                                        <td>{{ $dka->no_bilik }}</td>
                                        <td>{{ $dka->tipe_hunian }}</td>
                                        <td>{{ Carbon::parse($dka->tgl_pembayaran)->format('d-F-Y') }}</td>
                                        <td>@currency($dka->total_pembayaran)</td>
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
                </div>
            </div>
        </div>
    </div>
    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>


    {{-- DATATABLES TOTAL SCRIPT --}}
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                searching: false,
                info: false,
                ordering: false,
                scrollX: true,
                // paging: true,
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
                        formatter.format(pageTotal).replace(/[,]/g, '') + ' ( ' + formatter.format(
                            total).replace(
                            /[,]/g, '') + ' total)';
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#myModal1 form').on('submit', function(event) {
                event.preventDefault();
                const startDate = $('#myModal1 #startDate').val();
                const endDate = $('#myModal1 #endDate').val();
                $.ajax({
                    url: '/admin/{{ $id }}/laporanKeuangan/report',
                    method: 'GET',
                    data: {
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(response) {
                        $('#printPreviewContainer').html(response);
                        window.print();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
@stop
