@extends('layouts.default')
@section('content')
	<div class="container">
		<div class="container mt-2">
			<div class="card bg-light mb-3 mx-auto " style="width: 25rem;">
				<div class="card-header text-center">Tambah Properti</div>
				<div class="card-body">
					<div class="col">
						<form action="{{ route('rspradmin', ['id' => Auth::user()->id]) }}" method="POST">
							@csrf
							<div class="mb-3">
								<label for="jenis_properti" class="form-label">Jenis Properti</label>
								<select class="form-select form-select-sm" id="tipe_hunian" name="jenis_properti" required>
									<option disabled value="">Jenis Properti</option>
									<option value="Kios">Kios</option>
									<option value="Kontrakan">Kontrakan</option>
									<option value="Kost">Kost</option>
								</select>
	
								<div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
									option is required.</div>
							</div>
							<div class="mb-3">
								<label class="form-label" for="jumlahBilik">Jumlah Bilik</label>
								<input class="form-control form-control-sm" id="jumlahBilik" type="number" name="jumlah_bilik"
									placeholder="Jumlah Bilik" data-sb-validations="required" required />
								<div class="invalid-feedback" data-sb-feedback="jumlahBilik:required">Jumlah Bilik
									is required.</div>
							</div>
							<div class="mb-3">
								<label class="form-label" for="alamat">Alamat</label>
								<textarea class="form-control form-control-sm" id="alamat" type="text" name="alamat" placeholder="Alamat"
									style="height: 10rem;" data-sb-validations="required" required></textarea>
								<div class="invalid-feedback" data-sb-feedback="alamat:required">Alamat is
									required.</div>
							</div>
							<div class="d-grid">
								<button class="btn btn-primary " id="submitButton"
									type="submit">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>



	{{-- <div class="container my-lg-5">
		<div class="container mt-lg-5">
			<div class="row justify-content-center">
				
			</div>
			<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
		</div>
	</div> --}}
@stop