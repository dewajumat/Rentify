@extends('layouts.default')
@section('content')
    <div class="container">
        <div class="container mt-2">
            <div class="card bg-light mb-3 mx-auto " style="width: 25rem;">
                <div class="card-header text-center">Edit Properti</div>
                <div class="card-body">
                    <div class="col">
                        @foreach ($dataProperti as $dp)
                        <form action="{{ route('rsupradmin', ['id' => Auth::user()->id, 'properti_id' => $dp->properti_id]) }}" method="POST">
                            @csrf
                                <div class="mb-3">
                                    <label for="jenisKelamin" class="form-label">Jenis Properti</label>
                                    <select class="form-select form-select-sm" id="tipe_hunian" name="jenis_properti"
                                        required>
                                        <option disabled value="">Jenis Properti</option>
                                        <option value="Kios"
                                            {{ isset($dp->jenis_properti) && $dp->jenis_properti == 'Kios' ? 'selected' : '' }}>
                                            Kios</option>
                                        <option value="Kontrakan"
                                            {{ isset($dp->jenis_properti) && $dp->jenis_properti == 'Kontrakan' ? 'selected' : '' }}>
                                            Kontrakan</option>
                                        <option value="Kost"
                                            {{ isset($dp->jenis_properti) && $dp->jenis_properti == 'Kost' ? 'selected' : '' }}>
                                            Kost</option>
                                    </select>

                                    <div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
                                        option is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="alamat">Alamat</label>
                                    <textarea class="form-control form-control-sm" id="alamat" type="text" name="alamat" placeholder="Alamat" style="height: 10rem;" data-sb-validations="required" required>@isset($dp->alamat){{ $dp->alamat }}@endisset</textarea>
                                    <div class="invalid-feedback" data-sb-feedback="alamat:required">Alamat isrequired.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="jumlahBilik">Jumlah Bilik</label>
                                    <input class="form-control form-control-sm" id="jumlahBilik" type="number"
                                        name="jumlah_bilik" placeholder="Jumlah Bilik"data-prev-value="{{ $dp->jumlah_bilik }}"
                                        data-sb-validations="required" value="{{ $dp->jumlah_bilik }}" required />
                                    <div class="invalid-feedback" data-sb-feedback="jumlahBilik:required">Jumlah Bilik
                                        is required.</div>
                                    <div class="invalid-feedback" data-sb-feedback="jumlahBilik:minValue">Jumlah Bilik harus lebih besar dari jumlah bilik sebelumnya.</div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary " id="submitButton" type="submit">Simpan</button>
                                </div>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('jumlahBilik').addEventListener('input', function() {
            var input = this;
            var prevValue = input.getAttribute('data-prev-value');
    
            if (parseFloat(input.value) < parseFloat(prevValue)) {
                input.setCustomValidity('Jumlah Bilik harus lebih besar dari jumlah bilik sebelumnya.');
            } else {
                input.setCustomValidity('');
            }
        });
    </script>
    



    {{-- <div class="container my-lg-5">
		<div class="container mt-lg-5">
			<div class="row justify-content-center">
				
			</div>
			<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
		</div>
	</div> --}}
@stop
