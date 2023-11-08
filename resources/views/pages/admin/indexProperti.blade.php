@extends('layouts.default')
@section('content')

@php
    use App\Models\Bilik; 
@endphp

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

        /* .page-item:hover {
                                           background-color: lightgray !important;
                                           border: none;
                                          } */
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
            .btn-add {
                text-align: center !important;
            }

            .card {
                width: 25rem
            }
        }

        td .child {
            text-align: left;
        }

        span.dtr-title {
            min-width: 145px !important;
        }
    </style>

    {{-- <div class="container">
        <div class="container mt-2">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Halaman Properti admin dengan id {{ $id }}</div>
                <div class="card-body">
                    <h4 class="card-title">Card Title</h4>
                    <p class="card-text">This is a card text paragraph with light color background and aligned
                        center.</p>
                </div>
            </div>
        </div>
    </div> --}}

    @if (Session::has('successMessage'))
        <div class="container">
            <div class="alert alert-primary" id="successMessage">
                {{ Session::get('successMessage') }}
            </div>
        </div>
    @endif

    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header fw-bold">Data Properti</div>
                <div class="card-body">
                    <div class="container my-4">
                        <div class="col mb-2">
                            <div class="">
                                @if ($allFieldsNull)
                                    <div class="text-end btn-add">
                                        <a class="btn btn-primary disabled-link btn-add" style=""
                                            href="{{ route('rapradmin', ['id' => Auth::user()->id]) }}"
                                            role="button">Tambah
                                            Properti</a>
                                    </div>
                                @elseif (!$allFieldsNull)
                                    <div class="text-end btn-add">
                                        <a class="btn btn-primary btn-add" style=""
                                            href="{{ route('rapradmin', ['id' => Auth::user()->id]) }}"
                                            role="button">Tambah
                                            Properti</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Jenis Properti</th>
                                    <th class="text-center">Jumlah Bilik</th>
                                    <th class="text-center">Terisi</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataProperti as $dp)
                                    <tr>
                                        <td>{{ $dp->jenis_properti }}</td>
                                        <td>{{ $dp->jumlah_bilik }}</td>
                                        <td>{{ $dp->bilik_count }}
                                        {{-- <td>
                                            {{ Bilik::where('properti_id', $dp->jenis_properti)
                                            ->where('isFilled', 'true')
                                            ->count() }} --}}
                                        </td>
                                        <td>{{ $dp->alamat }}</td>
                                        <td>
                                            <div class="" style="display:inline-flex">
                                                <div class="">
                                                    <a
                                                        href="{{ route('rdpradmin', ['id' => Auth::user()->id, $dp->properti_id]) }}">
                                                        <div class="text-center">
                                                            <i class="fa-solid fa-eye action-icon detEye"
                                                                data-id="{{ $dp->properti_id }}"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                &emsp;
                                                <div class="">
                                                    <a
                                                        href="{{ route('rupradmin', ['id' => Auth::user()->id, $dp->properti_id]) }}">
                                                        <div class="text-center">
                                                            <i class="fa-solid fa-pencil action-icon" data-bs-toggle="modal"
                                                                data-bs-target="#myModal2"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                &emsp;
                                                <div class="">
                                                    <a href="#">
                                                        <button
                                                            style="border: none; background-color: transparent; padding: 0; font-size: inherit; cursor: pointer;"
                                                            type="button" class="modal2" data-id="{{ $dp->properti_id }}">
                                                            <i class="fas fa-trash pe-none" style="color: rgb(228, 12, 12)"
                                                                data-bs-toggle="modal" data-bs-target="#myModal2"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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

    {{-- Delete Properti --}}
    <script>
        $(window).on('load', function() {
            $('#myModal2').modal('hide');

            $(document).ready(function() {
                $('.modal2').click(function(event) {
                    event.preventDefault();

                    const properti_id = $(this).attr('data-id');
                    const id = '{{ Auth::user()->id }}';

                    if (confirm('Hapus properti ini ?')) {
                        $.ajax({
                            url: 'deleteProperti/' + properti_id,
                            dataType: 'json',
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "properti_id": properti_id
                            },
                            success: function(data) {
                                console.log(data);
                                window.location.reload(); // Reload the page
                            },
                            error: function(xhr, status, error) {
                                console.log('AJAX request error:', error);
                            }
                        });
                    }
                });
            });

        });
    </script>
@stop
