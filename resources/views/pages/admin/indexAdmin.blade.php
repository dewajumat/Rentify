@extends('layouts.default')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
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

        span.dtr-title {
            min-width: 145px !important;
        }
    </style>


    @if ($allFieldsNull)
        <div class="container">
            <div class="container">
                <div class="alert alert-warning">
                    Silahkan lengkapi data diri Anda pada menu Profile
                </div>
            </div>
        </div>
    @elseif (!$allFieldsNull && $adminProperti->isEmpty())
        <div class="container">
            <div class="container">
                <div class="alert alert-warning">
                    Silahkan tambahkan properti pada sidebar menu properti
                </div>
            </div>
        </div>
    @endif

    @if (Session::has('successMessage'))
        <div class="container">
            <div class="container">
                <div class="alert alert-warning" id="successMessage">
                    {{ Session::get('successMessage') }}
                </div>
            </div>
        </div>
    @endif




    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 ">
                <div class="card-header fw-bold text-center">Data Penghuni</div>
                <div class="card-body">
                    <div class="container my-4">
                        <div class="col mb-2">
                            <div class="">
                                @if ($adminProperti->isEmpty())
                                    <div class="text-end btn-add">
                                        <a class="btn btn-primary disabled-link" style=""
                                            href="{{ route('rapadmin', ['id' => Auth::user()->id]) }}" role="button">Tambah
                                            Penghuni</a>
                                    </div>
                                @else()
                                    <div class="text-end btn-add">
                                        <a class="btn btn-primary " style=""
                                            href="{{ route('rapadmin', ['id' => Auth::user()->id]) }}" role="button">Tambah
                                            Penghuni</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <table id="example" class="table table-striped table-bordered display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Jenis Properti</th>
                                    <!--<th class="text-center">Alamat</th>-->
                                    <th class="text-center">Bilik</th>
                                    <th class="text-center">Tipe Hunian</th>
                                    <th class="text-center">Status Pembayaran</th>
                                    <th class="text-center">Status Hunian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPenghuni as $dp)
                                    <tr>
                                        <td>{{ $dp->name }}</td>
                                        <td>{{ $dp->jenis_properti }}</td>
                                        @php
                                            $words = explode(' ', $dp->alamat);
                                            $alamat = implode(' ', array_slice($words, 0, 3));
                                        @endphp
                                        <!--<td>{{ $alamat }}</td>-->
                                        <td>{{ $dp->no_bilik }}</td>
                                        <td>{{ $dp->tipe_hunian }}</td>
                                        <td>
                                            @if ($dp->status_pembayaran === 'Pending')
                                                <a href="" class="btn btn-danger btn-sm disabled">
                                                    {{ $dp->status_pembayaran }}
                                                </a>
                                            @elseif ($dp->status_pembayaran === 'Belum bayar')
                                                <a href="" class="btn btn-danger btn-sm disabled">
                                                    {{ $dp->status_pembayaran }}
                                                </a>
                                            @elseif ($dp->status_pembayaran === 'Diproses')
                                                @php
                                                    $newestRecord = DB::table('bilik')
                                                        ->join('pembayaran', function ($join) {
                                                            $join->on('bilik.bilik_id', '=', 'pembayaran.bilik_id');
                                                        })
                                                        ->select('pembayaran.pembayaran_id')
                                                        ->where('bilik.bilik_id', $dp->bilik_id)
                                                        ->latest('pembayaran.pembayaran_id')
                                                        ->first();
                                                    $newestRecordId = $newestRecord ? $newestRecord->pembayaran_id : null;
                                                @endphp
                                                <a href="{{ route('rKonPembayaran', ['id' => Auth::user()->id, 'penghuni_id' => $dp->penghuni_id, 'bilik_id' => $dp->bilik_id, 'pembayaran_id' => $newestRecordId]) }}"
                                                    class="btn btn-warning btn-sm">
                                                    {{ $dp->status_pembayaran }}
                                                </a>
                                            @elseif ($dp->status_pembayaran === 'Terbayar')
                                                <a href="#" class="btn btn-success btn-sm disabled">
                                                    {{ $dp->status_pembayaran }}
                                                </a>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($dp->status_hunian === 'Tidak Aktif')
                                                <a href="" class="btn btn-primary btn-sm disabled">
                                                    {{ $dp->status_hunian }}
                                                </a>
                                            @elseif ($dp->status_hunian === 'Dibatalkan')
                                                <a href="{{ route('rCanceledHunian', [$id, $dp->bilik_id]) }}"
                                                    class="btn btn-danger btn-sm">
                                                    {{ $dp->status_hunian }}
                                                </a>
                                            @elseif ($dp->status_hunian === 'Perlu Konfirmasi')
                                                <a href="#" class="btn btn-primary btn-sm disabled">
                                                    {{ $dp->status_hunian }}
                                                </a>
                                            @elseif ($dp->status_hunian === 'Pending')
                                                <a href="#" class="btn btn-danger btn-sm disabled">
                                                    {{ $dp->status_hunian }}
                                                </a>
                                            @elseif ($dp->status_hunian === 'Diproses')
                                                <a href="" class="btn btn-warning btn-sm  disabled">
                                                    {{ $dp->status_hunian }}
                                                </a>
                                            @elseif ($dp->status_hunian === 'Aktif')
                                                <a href="#" class="btn btn-success btn-sm  disabled">
                                                    {{ $dp->status_hunian }}
                                                </a>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="" style="display:inline-flex">
                                                <div class="">
                                                    <div class="">
                                                        @if ($dp->status_hunian == 'Aktif')
                                                            <a href="{{ route('rDBilAdmin', ['id' => Auth::user()->id, $dp->bilik_id]) }}"
                                                                class="">
                                                                <button
                                                                    style="border: none; background-color: transparent; padding: 0; font-size: inherit; cursor: pointer;"
                                                                    type="button" class=""
                                                                    value="{{ $dp->bilik_id }}">
                                                                    <i class="fas fa-eye pcl"></i>
                                                                </button>
                                                            </a>
                                                        @else
                                                            <a href="#" class="disabled-link ">
                                                                <button
                                                                    style="border: none; background-color: transparent; padding: 0; font-size: inherit; cursor: pointer;"
                                                                    type="button" class="modal1 disabled-link"
                                                                    data-id="{{ $dp->bilik_id }}" disabled>
                                                                    <i class="fas fa-eye pcl disabled-link"
                                                                        data-bs-toggle="modal" data-bs-target="#myModal1"
                                                                        disabled></i>
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                &emsp;
                                                &emsp;
                                                <div class="">
                                                    <button
                                                        style="border: none; background-color: transparent; padding: 0; font-size: inherit; cursor: pointer;"
                                                        type="button" class="modal2" data-id="{{ $dp->bilik_id }}">
                                                        <i class="fas fa-trash pe-none" style="color: rgb(228, 12, 12)"
                                                            data-bs-toggle="modal" data-bs-target="#myModal2"></i>
                                                    </button>
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
            {{-- DataTables JS --}}
            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
            <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


            <script>
                $(document).ready(function() {
                    const dataTable = $('#example').DataTable({
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

                    // Bind event handler to delete button
                    $(document).on('click', '.modal2', function(event) {
                        console.log('Button clicked!');
                        event.preventDefault();

                        const bilik_id = $(this).attr('data-id');
                        const id = '{{ Auth::user()->id }}';

                        // Show SweetAlert with "Hapus" and "Cancel" buttons
                        swal({
                            title: "Hapus penghuni bilik ini?",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        }).then((result) => {
                            if (result) {
                                $.ajax({
                                    url: '/admin/' + id + '/deleteRecordBilik/' + bilik_id,
                                    dataType: 'json',
                                    type: 'DELETE',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "bilik_id": bilik_id
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        window.location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        console.log('AJAX request error:',
                                        error);
                                    }
                                });
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
@stop
