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
                <div class="card-header text-center fw-bold">Profile Admin</div>
                <div class="card-body">
                    <div class="col">
                        <form action="{{ route('rStoreProfileAdmin', ['id' => Auth::user()->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label" for="fotoProfil">Foto Profile</label>
                                <input class="form-control" id="fotoProfil" type="file" name="foto" placeholder=""
                                    data-sb-validations="required" />
                                @error('foto')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @isset($dataAdmin->foto)
                                <span class="ms-1">
                                    <small>
                                        <a href="#" style="color: black" data-bs-toggle="modal"
                                            data-bs-target="#profileModal">{{ basename($dataAdmin->foto) }}</a>
                                    </small>
                                </span>
                            @endisset
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="nik">NIK</label>
                                <input class="form-control" id="nik" type="file" name="nik" placeholder=""
                                    data-sb-validations="required" />
                                @error('nik')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @isset($dataAdmin->nik)
                                <span class="ms-1">
                                    <small>
                                        <a href="#" style="color: black" data-bs-toggle="modal"
                                            data-bs-target="#nikModal">{{ basename($dataAdmin->nik) }}</a>
                                    </small>
                                </span>
                            @endisset
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="name">Nama</label>
                                <input class="form-control" id="name" name="name" type="text" placeholder="Nama"
                                    value="{{ $dataAdmin->name }}" required>
                            </div>

                            <div class="mb-2">
                                <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenisKelamin" name="jenis_kelamin" required readonly>
                                    <option disabled selected value="">Jenis Kelamin</option>
                                    <option value="Pria"
                                        {{ isset($dataAdmin->jenis_kelamin) && $dataAdmin->jenis_kelamin == 'Pria' ? 'selected' : '' }}>
                                        Pria
                                    </option>
                                    <option value="Wanita"
                                        {{ isset($dataAdmin->jenis_kelamin) && $dataAdmin->jenis_kelamin == 'Wanita' ? 'selected' : '' }}>
                                        Wanita
                                    </option>
                                </select>

                                <div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
                                    option is required.</div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label" for="nomorHandhpone">Nomor Handhpone</label>
                                <input class="form-control" id="nomorHandhpone" name="no_handphone" type="text"
                                    placeholder="Nomor Handhpone" value="{{ $dataAdmin->no_handphone }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="text" placeholder="Email"
                                    value="{{ $dataAdmin->email }}" requireds>
                            </div>
                            <div class="d-grid text-center">
                                <a href="">
                                    <button class="btn btn-primary btn-md w-100 mb-3" id="submitButton"
                                        type="submit">Simpan</button>
                                </a>
                            </div>
                    </div>
                    <!-- Modal for displaying the NIK image -->
                    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="nikModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="nikModalLabel">Foto Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ asset('image/'.basename($dataAdmin->foto)) }}" class="img-fluid" alt="NIK Image">
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
                                    <img src="{{ asset('image/'.basename($dataAdmin->nik)) }}" class="img-fluid" alt="NIK Image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
