@extends('layouts.default')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        .page-item.active .page-link {
            background-color: #2185D5 !important;
            border: none;
        }

        .page-link {
            color: dark !important;
            border: none;
        }
        
        .modal-confirm {
            color: #636363;
            width: 400px;
        }

        .custom-dialog {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .custom-dialog-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .custom-dialog-header {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .custom-dialog-message {
            margin-bottom: 20px;
        }

        .custom-dialog-actions button {
            margin-right: 10px;
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

        @media screen and (max-width: 480px) {
            .card {
                width: 25rem
            }

            .detProp1 {
                margin-left: 0px !important;
            }

            .detProp2 {
                margin-left: 9px !important;
            }

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
                <div class="card-header text-center fw-bold">Detail Properti</div>
                <div class="card-body mx-2 my-1">
                    @foreach ($dataProperti as $dp)
                        <div class="" style="display: inline-flex"> 
                            <div class="detProp1">
                                <div class="text-left">Jenis Properti : {{ $dp->jenis_properti }}</div>
                                <div class="text-left mt-2">Alamat : {{ implode(' ', array_slice(explode(' ', $dp->alamat), 0, 3)) }}</div>
                            </div>
                            <div class="detProp2" style="margin-left: 14rem">
                                <div class="text-left">Jumlah Bilik : {{ $dp->jumlah_bilik }}</div>
                                <div class="text-left mt-2">Bilik Terisi : {{ $dp->bilik_count }}
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
                <div class="card-header fw-bold">Data Penghuni</div>
                <div class="card-body">
                    <div class="">
                        <div class="container my-2">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Penghuni</th>
                                        <th class="text-center">No. Bilik</th>
                                        <th class="text-center">Status Hunian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detProp as $dp)
                                        <tr>
                                            <td>{{ $dp->name }}</td>
                                            <td>{{ $dp->no_bilik }}</td>
                                            <td>{{ $dp->status_hunian }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- First Modal -->
                            <div class="modal fade" id="myModal1" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>bilikId: <span id="bilikIdValue"></span></p>
                                            <p>indexPenghuni: <span id="indexPenghuniValue"></span></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary">Save changes</button>
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
                        targets: -2
                    },
                ]
            });
        });
    </script>
@stop
