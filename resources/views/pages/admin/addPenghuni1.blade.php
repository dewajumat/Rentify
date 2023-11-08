@extends('layouts.default')
@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.6.0/slimselect.css"
        integrity="sha512-ijXMfMV6D0xH0UfHpPnqrwbw9cjd4AbjtWbdfVd204tXEtJtvL3TTNztvqqr9AbLcCiuNTvqHL5c9v2hOjdjpA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        select[data-ssid] {
            pointer-events: none;
            opacity: 0;
            display: flex !important;
            position: absolute;
        }
    </style>

    <div class="container ">
        <div class="container">
            <div class="card bg-light mb-3 text-left mx-auto " style="width: 25rem;">
                <div class="card-header text-center fw-bold">Tambah Penghuni</div>
                <div class="card-body ">
                    <div class="col ">
                        <form action="{{ route('rspadmin', ['id' => Auth::user()->id]) }}" method="POST">
                            @csrf
                            <div class="mb-1">
                                <label for="name" class="form-label">Nama Penghuni</label>
                                <select class="" id="dropdown" name="dropdown"
                                    style="width: 100%;
                                            padding: 0.375rem 0.6rem 0.375rem 0.375rem;
                                            font-size: 0.875rem;
                                            font-weight: 400;
                                            line-height: 1;
                                            color: #212529;
                                            background-color: #fff;
                                            border: 1px solid #ced4da;
                                            border-radius: 0.35rem;display:flex">
                                    <option disabled selected hidden value="">Nama Penghuni</option>
                                    @foreach ($namaCalonPenghuni as $ncp)
                                        {{-- @if (!is_null($ncp->jenis_kelamin) && !is_null($ncp->nik) && !is_null($ncp->no_kk) && !is_null($ncp->no_handphone)) --}}
                                            <option value="{{ $ncp->penghuni_id }}">{{ $ncp->name }}</option>
                                        {{-- @endif --}}
                                    @endforeach
                                </select>
                                @if ($errors->has('dropdown'))
                                    <div class="invalid-feedback" id="dropdownError" style="display: block;font-size:12px">
                                        {{ $errors->first('dropdown') }}</div>
                                @else
                                    <div class="invalid-feedback" id="dropdownError" style="display: none;">One option is
                                        required.</div>
                                @endif

                            </div>
                            <div class="mb-1">
                                <label for="jenis_properti" class="form-label">Jenis Properti</label>
                                <select class="form-select form-select-sm" id="jenis_properti" name="properti_id" required>
                                    <option disabled selected hidden value="">Jenis Properti</option>
                                    @foreach ($dataProperti as $dp)
                                        <option value="{{ $dp->properti_id }}">{{ $dp->jenis_properti }},
                                            {{ $dp->alamat }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
                                    option is required.</div>
                            </div>
                            <div class="mb-1">
                                <label for="jumlah_bilik" class="form-label">Nomor Bilik</label>
                                <select class="form-select form-select-sm" id="jumlah_bilik" name="jumlah_bilik" required>
                                    <option disabled hidden value="">Bilik</option>
                                </select>
                                <div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
                                    option is required.</div>
                            </div>
                            <div class="mb-1">
                                <label for="tipe_hunian" class="form-label">Tipe Hunian:</label>
                                <select class="form-select form-select-sm" id="tipe_hunian" name="tipe_hunian" required>
                                    <option disabled selected value="">Tipe Hunian</option>
                                    <option value="Perbulan">Perbulan</option>
                                    <option value="Per6bulan">Per6bulan</option>
                                    <option value="Pertahun">Pertahun</option>
                                </select>
                                <div class="invalid-feedback" data-sb-feedback="tipe_hunian:required">One
                                    option is required.</div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="total_pembayaran">Total Pembayaran</label>
                                <input class="form-control form-control-sm" id="rupiah" name="total_pembayaran"
                                    type="text" placeholder="" value="" required>
                            </div>
                            <div class="mb-2">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-md" id="submitButton" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>






    {{-- jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>

    <script>
        var slim = new SlimSelect({
            select: '#dropdown'
        });
    </script>

    {{-- Rupiah Input --}}
    <script src="{{ asset('js/rupiah-input.js') }}"></script>

    <script>
        function validateDropdown() {
            var dropdown = document.getElementById('dropdown');
            var dropdownError = document.getElementById('dropdownError');

            if (dropdown.value === '') {
                dropdown.classList.add('is-invalid');
                dropdownError.style.display = 'block';
            } else {
                dropdown.classList.remove('is-invalid');
                dropdownError.style.display = 'none';
            }
        }
    </script>



    <script>
        $(document).ready(function() {
            // Get the building dropdown element
            var buildingDropdown = document.getElementById("jenis_properti");
            // Get the door dropdown element
            var doorDropdown = document.getElementById("jumlah_bilik");

            // Function to update door dropdown options
            function updateDoorDropdown() {
                // Clear existing options
                doorDropdown.innerHTML = '<option disabled value="">Bilik</option>';

                // Get the selected building value
                var selectedBuilding = buildingDropdown.value;

                // Generate options for the selected building
                var dataProperti = <?php echo json_encode($dataProperti); ?>;
                var dataBilik = <?php echo json_encode($dataBilik); ?>;

                dataProperti.forEach(element => {
                    if (selectedBuilding === element.properti_id) {
                        for (var i = 1; i <= element.jumlah_bilik; i++) {
                            var shouldSkip = false;
                            dataBilik.forEach(bilik => {
                                if (i === bilik.no_bilik && selectedBuilding === bilik
                                    .properti_id) {
                                    shouldSkip = true;
                                }
                            });

                            if (shouldSkip) {
                                continue; // Skip the current iteration
                            }

                            var option = document.createElement("option");
                            option.text = "Bilik " + i;
                            option.value = i;
                            doorDropdown.add(option);
                        }
                    }
                });
            }

            // Call the updateDoorDropdown() function when the building dropdown value changes
            buildingDropdown.addEventListener("change", updateDoorDropdown);

            // Initial call to populate the door dropdown when the page loads
            updateDoorDropdown();
        });
    </script>
@stop
