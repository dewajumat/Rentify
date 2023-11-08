@extends('layouts.default')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        .table thead th {
            vertical-align: middle;
        }
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
        .table thead th {
            vertical-align: middle;
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
        @media screen and (max-width: 480px) {
            .btn-add {
                text-align: center !important;
            }
            .card {
                width: 25rem
            }
        }
    </style>
    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Riwayat Pembayaran</div>
                <div class="card-body">
                    <div class="container mt-2">
                        <form action="{{ route('rGenPayment', ['id' => $id]) }}">
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
                                <button class="btn btn-primary mt-2" type="submit">
                                    Buat Laporan Keuangan
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="container my-4">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Jenis Properti</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Bilik</th>
                                    <th class="text-center">Tipe Hunian</th>
                                    <th class="text-center">Tanggal Pembayaran</th>
                                    <th class="text-center">Periode Terbayar</th>
                                    <th class="text-center">Terbilang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPembayaranPenghuni as $dpp)
                                    <tr>
                                        <td>{{ $dpp->jenis_properti }}</td>
                                        <td>{{ $dpp->alamat }}</td>
                                        <td>{{ $dpp->no_bilik }}</td>
                                        <td>{{ $dpp->tipe_hunian }}</td>
                                        <td>{{ Carbon::parse($dpp->tgl_pembayaran)->format('d F Y') }}</td>
                                        <td>{{ Carbon::parse($dpp->bulan_start_terbayar)->format('F Y') }} -
                                            {{ Carbon::parse($dpp->bulan_end_terbayar)->format('F Y') }}</td>
                                        <td>@currency($dpp->total_pembayaran)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="">
                                <tr>
                                    <th colspan="6" style="text-align:right">Total:</th>
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
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                searching: false,
                info: false,
                ordering: false,
                scrollX: true,
                "footerCallback": function(row, data, start, end, display) {
                    let api = this.api();
                    let intVal = function(i) {
                        return typeof i === 'string' ?
                            parseInt(i.replace(/[.,]/g, '').replace('Rp ', '')) :
                            typeof i === 'number' ?
                            i :
                            0;
                    };
                    total = api
                        .column(6)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    pageTotal = api
                        .column(6, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    let formatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    api.column(6).footer().innerHTML =
                        formatter.format(pageTotal).replace(/[,]/g, '') + ' ( ' + formatter.format(
                            total).replace(
                            /[,]/g, '') + ' total)';
                }
            });
        });
    </script>
    <script>
        const dropdown = document.getElementById('dropdown');
        const form = document.getElementById('myForm');

        form.addEventListener('submit', function(event) {
            if (!dropdown.value) {
                event.preventDefault(); // Prevent form submission if no option is selected
            } else {
                const url = new URL(form.action);
                url.searchParams.set('penghuniId', dropdown.value);
                form.action = url.toString();
            }
        });
    </script>
@stop
