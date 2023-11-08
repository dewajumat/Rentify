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
                width: 25rem;
            }

            .alert {
                width: 25rem;
            }
        }

        td .child {
            text-align: left;
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
    @elseif (!$allFieldsNull && $bilikPenghuni->isEmpty())
        <div class="container">
            <div class="container">
                <div class="alert alert-warning">
                    Silahkan menunggu tautan bilik dari Admin
                </div>
            </div>
        </div>
    @endif

    <div class="container my-lg-5">
        <div class="container mt-lg-5">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Dashboard Hunian</div>
                <div class="card-body">
                    <div class="container my-4" style="">
                        <table id="example" class="table table-striped table-bordered display nowrap" style="width:100%">
                            <thead style="">
                                <tr>
                                    <th style="text-align: center">Jenis Properti</th>
                                    <th style="text-align: center">Alamat</th>
                                    <th style="text-align: center">Bilik</th>
                                    <th style="text-align: center">Tipe Hunian</th>
                                    <th style="text-align: center">Status Pembayaran</th>
                                    <th style="text-align: center">Status Hunian </th>
                                    <th style="text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataIndexPenghuni as $dip)
                                    <tr>
                                        <td>{{ $dip->jenis_properti }}</td>
                                        <td>{{ $dip->alamat }}</td>
                                        <td>{{ $dip->no_bilik }}</td>
                                        <td>{{ $dip->tipe_hunian }}</td>
                                        <td>
                                            @php
                                                $newestRecord = DB::table('bilik')
                                                    ->join('pembayaran', function ($join) {
                                                        $join->on('bilik.bilik_id', '=', 'pembayaran.bilik_id');
                                                    })
                                                    ->select('pembayaran.pembayaran_id')
                                                    ->where('bilik.bilik_id', $dip->bilik_id)
                                                    ->where('pembayaran.stat_pembayaran', 'Pending')
                                                    ->latest('pembayaran.pembayaran_id')
                                                    ->first();
                                                $newestRecordId = $newestRecord ? $newestRecord->pembayaran_id : null;
                                            @endphp
                                            @if ($dip->status_pembayaran === 'Pending')
                                                <a href="{{ route('rRejectedPayment', ['id' => Auth::user()->id, 'bilik_id' => $dip->bilik_id, 'pembayaran_id' => $newestRecordId]) }}"
                                                    class="btn btn-danger btn-sm">
                                                    {{ $dip->status_pembayaran }}
                                                </a>
                                            @elseif ($dip->status_pembayaran === 'Belum bayar')
                                                <a href="{{ route('rPembayaranPenghuni', ['id' => Auth::user()->id, $dip->bilik_id]) }}"
                                                    class="btn btn-danger btn-sm">
                                                    {{ $dip->status_pembayaran }}
                                                </a>
                                            @elseif ($dip->status_pembayaran === 'Diproses')
                                                <a href="#" class="btn btn-warning btn-sm disabled-link disabled"
                                                    style="pointer-events: none; cursor: default;">
                                                    {{ $dip->status_pembayaran }}
                                                </a>
                                            @elseif ($dip->status_pembayaran === 'Terbayar')
                                                <a href="#" class="btn btn-success btn-sm disabled-link disabled"
                                                    style="pointer-events: none; cursor: default;">
                                                    {{ $dip->status_pembayaran }}
                                                </a>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($dip->status_hunian === 'Tidak Aktif')
                                                <a href="" class="disabled-link">
                                                    <button type="button" class="btn btn-primary btn-sm disabled"
                                                        value="{{ $dip->bilik_id }}">
                                                        {{ $dip->status_hunian }}
                                                    </button>
                                                </a>
                                            @elseif ($dip->status_hunian === 'Dibatalkan')
                                                <a href="" class="disabled-link">
                                                    <button type="button" class="btn btn-danger btn-sm disabled"
                                                        value="{{ $dip->bilik_id }}">
                                                        {{ $dip->status_hunian }}
                                                    </button>
                                                </a>
                                            @elseif ($dip->status_hunian === 'Perlu Konfirmasi')
                                                <a href="{{ route('rKonPenghuni', [$id, $dip->bilik_id]) }}">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        value="{{ $dip->bilik_id }}">
                                                        {{ $dip->status_hunian }}
                                                    </button>
                                                </a>
                                            @elseif ($dip->status_hunian === 'Pending')
                                                <a href="" class="disabled-link">
                                                    <button type="button" class="btn btn-danger btn-sm disabled"
                                                        value="{{ $dip->bilik_id }}">
                                                        {{ $dip->status_hunian }}
                                                    </button>
                                                </a>
                                            @elseif ($dip->status_hunian === 'Diproses')
                                                <a href="" class="disabled-link">
                                                    <button type="button" class="btn btn-warning btn-sm disabled"
                                                        value="{{ $dip->bilik_id }}">
                                                        {{ $dip->status_hunian }}
                                                    </button>
                                                </a>
                                            @elseif ($dip->status_hunian === 'Aktif')
                                                <a href="" class="disabled-link">
                                                    <button type="button" class="btn btn-success btn-sm disabled"
                                                        value="{{ $dip->bilik_id }}">
                                                        {{ $dip->status_hunian }}
                                                    </button>
                                                </a>
                                            @endif

                                        <td>
                                            <div class="" style="display:inline-flex">
                                                <div class="">
                                                    <div class="">
                                                        @if ($dip->status_hunian == 'Aktif')
                                                            <a href="{{ route('rdBilPenghuni', ['id' => $id, 'bilik_id' => $dip->bilik_id]) }}"
                                                                class="">
                                                                <button
                                                                    style="border: none; background-color: transparent; padding: 0; font-size: inherit; cursor: pointer;"
                                                                    type="button" class="modal1"
                                                                    data-id="{{ $dip->bilik_id }}">
                                                                    <i class="fas fa-eye" style="color: #616365"></i>
                                                                </button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('rdBilPenghuni', ['id' => $id, 'bilik_id' => $dip->bilik_id]) }}"
                                                                class="disabled-link">
                                                                <button
                                                                    style="border: none; background-color: transparent; padding: 0; font-size: inherit; cursor: pointer;"
                                                                    type="button" class="modal1 disabled-link"
                                                                    data-id="{{ $dip->bilik_id }}" disabled>
                                                                    <i class="fas fa-eye disabled-link"
                                                                        style="color: #616365" disabled></i>
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </div>
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
@stop
