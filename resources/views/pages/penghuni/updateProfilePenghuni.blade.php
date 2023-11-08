@extends('layouts.default')
@section('content')
    <style>
        @media screen and (max-width: 480px) {
            .card {
                width: 25rem !important;
            }
        }
    </style>
    <div class="container">
        <div class="container mt-2">
            <div class="card bg-light mb-3 text-left mx-auto " style="width: 30rem;">
                <div class="card-header text-center fw-bold">Profile Penghuni</div>
                <div class="card-body">
                    <div class="col">
                        <form action="{{ route('rStoreProfilePenghuni', ['id' => Auth::user()->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label" for="fotoProfil">Foto Profile</label>
                                <input class="form-control" id="fotoProfil" type="file" name="foto" placeholder=""
                                    data-sb-validations="required" />
                                    @error('foto')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @isset($dataPenghuni->foto)
                                <span class="ms-1">
                                    <small>
                                        <a href="#" style="color: black" data-bs-toggle="modal"
                                            data-bs-target="#profileModal">Lihat Foto Profil</a>
                                    </small>
                                </span>
                                @endisset
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="nik">Kartu Tanda Penduduk</label>
                                <input class="form-control" id="nik" type="file" name="nik" placeholder=""
                                    data-sb-validations="required" />
                                    @error('nik')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @isset($dataPenghuni->nik)
                                <span class="ms-1">
                                    <small>
                                        <a href="#" style="color: black" data-bs-toggle="modal"
                                            data-bs-target="#nikModal">Lihat Kartu Tanda Penduduk</a>
                                    </small>
                                </span>
                                @endisset
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="no_kk">Kartu Keluarga</label>
                                <input class="form-control" id="no_kk" type="file" name="no_kk" placeholder=""
                                    data-sb-validations="required" />
                                    @error('no_kk')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @isset($dataPenghuni->no_kk)
                                <span class="ms-1">
                                    <small>
                                        <a href="#" style="color: black" data-bs-toggle="modal"
                                            data-bs-target="#kkModal">Lihat Kartu Keluarga</a>
                                    </small>
                                </span>
                                @endisset
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="name">Nama</label>
                                <input class="form-control" id="name" name="name" type="text" placeholder="Nama"
                                    value="@isset($dataPenghuni->name){{ $dataPenghuni->name }}@endisset" required>
                            </div>

                            <div class="mb-2">
                                <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenisKelamin" name="jenis_kelamin" required readonly>
                                    <option disabled selected value="">Jenis Kelamin</option>
                                    <option value="Pria"
                                        {{ isset($dataPenghuni->jenis_kelamin) && $dataPenghuni->jenis_kelamin == 'Pria' ? 'selected' : '' }}>
                                        Pria
                                    </option>
                                    <option value="Wanita"
                                        {{ isset($dataPenghuni->jenis_kelamin) && $dataPenghuni->jenis_kelamin == 'Wanita' ? 'selected' : '' }}>
                                        Wanita
                                    </option>
                                </select>

                                <div class="invalid-feedback" data-sb-feedback="jenis_kelamin:required">One
                                    option is required.</div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label" for="nomorHandhpone">Nomor Handhpone</label>
                                <input class="form-control" id="nomorHandhpone" name="no_handphone" type="text"
                                    placeholder="Nomor Handhpone"
                                    value="@isset($dataPenghuni->no_handphone){{ $dataPenghuni->no_handphone }}@endisset"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="text" placeholder="Email"
                                    value="@isset($dataPenghuni->email){{ $dataPenghuni->email }}@endisset"
                                    requireds>
                            </div>
                            <div class="d-grid text-center">
                                <a href="">
                                    <button class="btn btn-primary btn-md w-100 mb-3" id="submitButton"
                                        type="submit">Simpan</button>
                                </a>
                            </div>
                    </div>
                    <!-- Modal for displaying the NIK image -->
                    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="fotoProfileModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="nikModalLabel">Foto Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ asset('image/'.basename($dataPenghuni->foto)) }}" class="img-fluid mx-auto d-block" alt="NIK Image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for displaying the NIK image -->
                    <div class="modal fade" id="nikModal" tabindex="-1" aria-labelledby="nikModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="nikModalLabel">Kartu Tanda Penduduk</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ asset('image/'.basename($dataPenghuni->nik)) }}" class="img-fluid mx-auto d-block" alt="NIK Image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for displaying the KK image -->
                    <div class="modal fade" id="kkModal" tabindex="-1" aria-labelledby="kkModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="nikModalLabel">Kartu Keluarga</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ asset('image/'.basename($dataPenghuni->no_kk)) }}" class="img-fluid mx-auto d-block" alt="NIK Image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
