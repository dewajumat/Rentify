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
            color: black !important;
            border: none;
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
    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Data Pemeliharaan</div>
                <div class="card-body">
                    <div class="container my-4">
                        <form id="myForm" action="{{ route('rAddPemeliharaan', ['id' => Auth::user()->id]) }}"
                            method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <select class="form-select" id="dropdown" name="dropdown">
                                        <option disabled selected value="">Pilih Properti Bilik</option>
                                        @foreach ($dataProperti as $dprop)
                                            <option value="{{ $dprop->bilik_id }}">
                                                {{ $dprop->jenis_properti . ': ' . $dprop->alamat . ': Bilik ' . $dprop->no_bilik }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-grid mb-2">
                                        <button class="btn btn-primary" type="submit">Sarankan Pemeliharaan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Jenis Properti</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Bilik</th>
                                    <th class="text-center">Judul</th>
                                    <th class="text-center">Status Pemeliharaan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPemeliharaan as $dplh)
                                    <tr>
                                        <td>{{ $dplh->jenis_properti }}</td>
                                        <td>{{ $dplh->alamat }}</td>
                                        <td>{{ $dplh->no_bilik }}</td>
                                        <td>{{ $dplh->judul }}</td>
                                        <td>{{ $dplh->status_pemeliharaan }}</td>
                                        <td>
                                            <div class="text-center" style="display: inline">
                                                <a
                                                    href="{{ route('rDetailPemeliharaan', ['id' => $id, 'pemeliharaan_id' => $dplh->pemeliharaan_id]) }}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        targets: 3
                    },
                    {
                        responsivePriority: 1,
                        targets: -2
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
