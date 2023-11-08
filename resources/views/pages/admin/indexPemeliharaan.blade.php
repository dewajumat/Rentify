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
            color: dark;
            border: none;
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
                <div class="card-header">Halaman Dashboard admin dengan id {{ $id }}</div>
                <div class="card-body">
                    <h4 class="card-title">Card Title</h4>
                    <p class="card-text">This is a card text paragraph with light color background and aligned
                        center.</p>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header fw-bold">Data Pemeliharaan</div>
                <div class="card-body">
                    <div class="container my-4">
                        <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama Penghuni</th>
                                    <th class="text-center">Jenis Properti</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Bilik</th>
                                    <th class="text-center">Judul</th>
                                    <th class="text-center">Status Pemeliharaan</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPemeliharaan as $dplh)
                                    <tr>
                                        <td>{{ $dplh->name }}</td>
                                        <td>{{ $dplh->jenis_properti }}</td>
                                        <td>{{ $dplh->alamat }}</td>
                                        <td>{{ $dplh->no_bilik }}</td>
                                        <td>{{ $dplh->judul }}</td>
                                        <td>
                                            @if ($dplh->status_pemeliharaan === 'Pending')
                                                <a
                                                    href="{{ route('ramadmin', [$id, $dplh->penghuni_id, $dplh->bilik_id, $dplh->pemeliharaan_id]) }}">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        value="{{ $dplh->pemeliharaan_id }}">
                                                        {{ $dplh->status_pemeliharaan }}
                                                    </button>
                                                </a>
                                            @elseif ($dplh->status_pemeliharaan === 'Ditolak')
                                                <a href="" class="disabled-link">
                                                    <button type="button" class="btn btn-danger btn-sm disabled"
                                                        value="{{ $dplh->pemeliharaan_id }}">
                                                        {{ $dplh->status_pemeliharaan }}
                                                    </button>
                                                </a>
                                            @elseif ($dplh->status_pemeliharaan === 'Diproses')
                                                <a href="{{ route('ramadmin', [$id, $dplh->penghuni_id, $dplh->bilik_id, $dplh->pemeliharaan_id]) }}"
                                                    class="">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        value="{{ $dplh->bilik_id }}">
                                                        {{ $dplh->status_pemeliharaan }}
                                                    </button>
                                                </a>
                                            @elseif ($dplh->status_pemeliharaan === 'Selesai')
                                                <a href="" class="disabled-link">
                                                    <button type="button" class="btn btn-primary btn-sm disabled"
                                                        value="">
                                                        {{ $dplh->status_pemeliharaan }}
                                                    </button>
                                                </a>
                                            @endif
                                        </td>
                                        <td>@currency($dplh->total)</td>
                                        <td>
                                            <div class="text-center" style="display: inline">
                                                <a
                                                    href="{{ route('rdmadmin', ['id' => $id, 'pemeliharaan_id' => $dplh->pemeliharaan_id]) }}">
                                                    <button
                                                        style="border: none; background-color: transparent; padding: 0; font-size: inherit; cursor: pointer;"
                                                        type="button" value="{{ $dplh->pemeliharaan_id }} ">
                                                        <i class="fas fa-eye text-primary"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- First Modal -->
                        {{-- @foreach ($dataPemeliharaan as $dplh)
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
                                            <p>{{ $dplh->bilik_id }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach --}}

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
                        targets: 4
                    },
                    {
                        responsivePriority: 1,
                        targets: 5
                    },
                ]
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
