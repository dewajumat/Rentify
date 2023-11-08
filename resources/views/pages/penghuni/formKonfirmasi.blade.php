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
                                <label for="name" class="form-label">Pemilik Properti</label>
                                <select class="form-select" id="name" name="name" required readonly
                                    disabled>
                                    @foreach ($dataBilikProp as $dbp)
                                        <option value="{{ $dbp->name }}">{{ $dbp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                <label for="alamat" class="form-label">Alamat</label>
                                <select class="form-select" id="alamat" name="alamat" required readonly
                                    disabled>
                                    @foreach ($dataBilikProp as $dbp)
                                        <option value="{{ $dbp->alamat }}">{{ $dbp->alamat }}</option>
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
                            <div class="mb-2" id="fieldContainer" style="">
                                <label for="ket_pembatalan" class="form-label">Keterangan Pembatalan</label>
                                <input class="form-control @error('ket_pembatalan') is-invalid @enderror" id="ket_pembatalan" name="ket_pembatalan" type="text"
                                    placeholder="Harap diisi jika ingin membatalkan tautan" value="">
                                    @error('ket_pembatalan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-2" style="display: grid">
                                <span style="font-size: 14px">Dengan mengklik Konfirmasi anda telah menyetujui</span>
                                <span style="font-size: 14px"><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal">Ketentuan Menghuni</a></span>
                            </div>
                            <div class="mb-1">
                                <div id="buttonContainer">
                                    <div class="d-grid mb-2">
                                        <button class="btn btn-primary btn-md" id="submitButton" name="buttonType"
                                            value="submitButton" type="submit">Konfirmasi</button>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-danger btn-md" id="cancelButton" name="buttonType"
                                            value="cancelButton" type="submit">Batalkan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Syarat dan Ketentuan Menghuni Properti</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ol>
                                            <li>
                                                <p><strong>Pembayaran Sewa : </strong>Penyewa membayar sewa
                                                    properti sesuai dengan kesepakatan yang telah ditentukan.</p>
                                            </li>
                                            <li>
                                                <p><strong>Jangka Waktu Kontrak : </strong>Kontrak memiliki jangka waktu
                                                    tertentu, bisa diperpanjang setelah berakhir.</p>
                                            </li>
                                            <li>
                                                <p><strong>Perawatan Properti : </strong>Penyewa menjaga dan membersihkan
                                                    properti. Kerusakan dilaporkan dan diperbaiki.</p>
                                            </li>
                                            <li>
                                                <p><strong>Penghuni Tambahan : </strong>Memerlukan izin pemilik sebelum ada
                                                    penghuni tambahan.</p>
                                            </li>
                                            <li>
                                                <p><strong>Penggunaan Properti : </strong>Properti hanya untuk tujuan yang
                                                    disepakati.</p>
                                            </li>
                                            <li>
                                                <p><strong>Kewajiban Kontrak Penuh : </strong>Penyewa setuju menjalankan
                                                    kontrak penuh sesuai waktu, tidak boleh memutus di pertengahan waktu
                                                    sewa yang telah dibayarkan sebelumnya.</p>
                                            </li>
                                            <li>
                                                <p><strong>Tidak Ada Pengembalian Pembayaran : </strong>Pembayaran sewa
                                                    sebelumnya tidak dikembalikan jika penyewa keluar sebelum kontrak
                                                    berakhir.</p>
                                            </li>
                                            <li>
                                                <p><strong>Kewajiban Pembayaran : </strong>Penyewa membayar sewa hingga
                                                    kontrak berakhir, meskipun keluar lebih awal.</p>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
