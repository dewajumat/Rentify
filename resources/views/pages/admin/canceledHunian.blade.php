@extends('layouts.default')
@section('content')

    <div class="container ">
        <div class="container">
            <div class="card bg-light mb-3 text-left mx-auto " style="width: 25rem;">
                <div class="card-header text-center fw-bold">Konfirmasi Hunian</div>
                <div class="card-body ">
                    <div class="col ">
                        <form action="{{ route('rSKonPenghuni', ['id' => $id, 'bilik_id' => $bilik_id]) }}" method="POST">
                            @csrf
                            <div class="mb-1">
                                <label for="jenis_properti" class="form-label">Jenis Properti</label>
                                <select class="form-select" id="jenis_properti" name="properti_id" required readonly
                                    disabled>
                                    @foreach ($dataBilikProp as $dbp)
                                        <option value="{{ $dbp->properti_id }}">{{ $dbp->jenis_properti }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="mb-1">
                                <label for="no_bilik" class="form-label">Nomor Bilik</label>
                                <select class="form-select" id="jumlah_bilik" name="no_bilik" required readonly disabled>
                                    @foreach ($dataBilikProp as $dbp)
                                        <option disabled hidden value="">Bilik</option>
                                        <option value="{{ $bilik_id }}">{{ $dbp->no_bilik }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-1">
                                <label for="tipe_hunian" class="form-label">Tipe Hunian</label>
                                <select class="form-select disabled-link" id="tipe_hunian" name="tipe_hunian" required
                                    readonly disabled>
                                    @foreach ($dataBilikProp as $dbp)
                                        <option disabled hidden value="">Bilik</option>
                                        <option value="">{{ $dbp->tipe_hunian }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="tipe_hunian" class="form-label">Total Pembayaran</label>
                                @foreach ($dataBilikProp as $dbp)
                                    <input class="form-control" id="rupiah" name="total_pembayaran" type="text"
                                        placeholder="" value="@currency($dbp->total_pembayaran)" required readonly disabled>
                                @endforeach
                            </div>
                            <div class="mb-3" id="fieldContainer" style="">
                                <label for="ket_pembatalan" class="form-label">Keterangan Pembatalan</label>
                                <input class="form-control @error('ket_pembatalan') is-invalid @enderror disabled-link" id="ket_pembatalan" name="ket_pembatalan" type="text"
                                    placeholder="Harap diisi jika ingin membatalkan tautan" value="{{ $dbp->ket_pembatalan }}" readonly disabled>
                                    @error('ket_pembatalan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
