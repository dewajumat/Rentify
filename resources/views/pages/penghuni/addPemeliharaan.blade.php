@extends('layouts.default')
@section('content')
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
</style>
	<div class="container">
		<div class="container mt-2">
			<div class="card bg-light mb-3 mx-auto " style="width: 25rem;">
				<div class="card-header text-center">Request Pemeliharaan</div>
				<div class="card-body">
					<div class="col">
						<form
						action="{{ route('rStorePemeliharaan', ['id' => Auth::user()->id, 'bilik_id' => $bilik_id]) }}"
						method="POST" enctype="multipart/form-data">
						@csrf
                        @foreach ($dataBilik as $db)
                        <div class="mb-3">
							<label class="form-label" for="">Jenis Properti</label>
							<input class="form-control" id="judul" name="judul" type="text"
								placeholder="Judul Pemeliharaan" value="{{ $db->jenis_properti .": ". $db->alamat }}" disabled required>
						</div>
                        <div class="mb-3">
							<label class="form-label" for="judul">Nomor Bilik</label>
							<input class="form-control" id="judul" name="judul" type="text"
								placeholder="Judul Pemeliharaan" value="{{ $db->no_bilik }}" disabled required>
						</div>
                        @endforeach
                        <div class="mb-3">
                            <label class="form-label" for="tgl_pengajuan">Tanggal Pengajuan</label>
                            <input class="form-control" id="tgl_pengajuan" type="date" name="tgl_pengajuan"
                                placeholder="Tanggal Pengajuan" data-sb-validations="required" required />
                            <div class="invalid-feedback" data-sb-feedback="tgl_pengajuan:required">
                                Tanggal Pengajuan is required.</div>
                        </div>
                        <div class="mb-3">
							<label class="form-label" for="judul">Judul</label>
							<input class="form-control" id="judul" name="judul" type="text"
								placeholder="Judul Pemeliharaan" value="" required>
						</div>
                        <div class="mb-3">
							<label class="form-label" for="deskripsi">Deskripsi</label>
							<textarea class="form-control" id="deskripsi" type="text" name="deskripsi" placeholder="Deskripsi"
								style="height: 10rem;" data-sb-validations="required" required></textarea>
							<div class="invalid-feedback" data-sb-feedback="Deskripsi:required">Deskripsi is
								required.</div>
						</div>
                        <div class="mb-3">
                            <label class="form-label" for="gambar">Foto Pemeliharaan yang diajukan</label>
                            <input class="form-control" id="gambar" type="file"
                                name="gambar" placeholder=""
                                data-sb-validations="required" required />
                            <div class="invalid-feedback" data-sb-feedback="Foto Pemeliharaan :required">
                                Foto Pemeliharaan is required.</div>
                        </div>
						<div class="mb-3">
							<div class="d-grid">
								<button class="btn btn-primary btn-md" id="submitButton"
									type="submit">Request</button>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop