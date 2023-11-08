@extends('layouts.default')
@section('content')

    <link rel="stylesheet" href="https://unpkg.com/slim-select@latest/dist/slimselect.css">

    <div class="container ">
        <div class="container">
            <div class="card bg-light mb-3 text-left mx-auto " style="width: 25rem;">
                <div class="card-header text-center fw-bold">Tambah Penghuni</div>
                <div class="card-body ">
                    <div class="col ">
                        @foreach ($dataBilikPenghuni as $dbp)
                        <form action="{{ route('rSUptBilPenghuni', ['id' => Auth::user()->id, $dbp->bilik_id]) }}" method="POST">
                            @csrf
                            <div class="mb-1">
                                <label for="name" class="form-label">Nama Penghuni</label>
                                <input class="form-control form-control-sm" id="name" name="name"
                                    type="text" placeholder="" value="{{ $dbp->name }}" required>
                                {{-- <select class="" id="dropdown" name="dropdown"
                                    style="width: 100%;
								padding: 0.375rem 0.6rem 0.375rem 0.375rem;
								font-size: 0.875rem;
								font-weight: 400;
								line-height: 1;
								color: #212529;
								background-color: #fff;
								border: 1px solid #ced4da;
								border-radius: 0.35rem;">
                                    <option disabled selected value="">Nama Penghuni</option>
                                    @foreach ($namaCalonPenghuni as $ncp)
                                        @if (!is_null($ncp->jenis_kelamin) && !is_null($ncp->nik) && !is_null($ncp->no_kk) && !is_null($ncp->no_handphone))
                                            <option value="{{ $ncp->penghuni_id }}">{{ $ncp->name }}</option>
                                        @endif
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="mb-1">
                                <label for="jenis_properti" class="form-label">Jenis Properti</label>
                                <select class="form-select form-select-sm" id="jenis_properti" name="properti_id" required>{{ $dbp->jenis_properti }}
                                </select>
                                <div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
                                    option is required.</div>
                            </div>
                            <div class="mb-1">
                                <label for="jumlah_bilik" class="form-label">Nomor Bilik</label>
                                <select class="form-select form-select-sm" id="jumlah_bilik" name="jumlah_bilik" required>{{ $dbp->no_bilik }}
                                </select>
                                <div class="invalid-feedback" data-sb-feedback="tipeHunian:required">One
                                    option is required.</div>
                            </div>
                            <div class="mb-1">
                                <label for="tipe_hunian" class="form-label">Tipe Hunian:</label>
                                <select class="form-select form-select-sm" id="tipe_hunian" name="tipe_hunian">
                                    <option disabled selected value="">Tipe Hunian</option>
                                    <option value="Perbulan"
                                {{ isset($dbp->tipe_hunian) && $dbp->tipe_hunian == 'Perbulan' ? 'selected' : '' }}>
                                Perbulan
                            </option>
                            <option value="Per6bulan"
                                {{ isset($dbp->tipe_hunian) && $dbp->tipe_hunian == 'Per6bulan' ? 'selected' : '' }}>
                                Perbulan
                            </option>
                            <option value="Pertahun"
                                {{ isset($dbp->tipe_hunian) && $dbp->tipe_hunian == 'Pertahun' ? 'selected' : '' }}>
                                Perbulan
                            </option>
                                </select>
                                <div class="invalid-feedback" data-sb-feedback="tipe_hunian:required">One
                                    option is required.</div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="total_pembayaran">Total Pembayaran</label>
                                <input class="form-control form-control-sm" id="rupiah" name="total_pembayaran"
                                    type="text" placeholder="" value="{{ $dbp->total_pembayaran }}" required>
                            </div>
                            <div class="mb-2">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-md" id="submitButton" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </div>






    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- bootstrap --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script> --}}

    {{-- Slim Select --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.5.1/slimselect.min.js"
        integrity="sha512-PWzfW6G+AwNx/faHiIF20Q+enGoRndfrJrVc0JGj1y59W6WxkpzCfe0tt34qqK9bCFAXCE/t/O7nzQ8WXnw1vQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        new SlimSelect({
            select: '#dropdown'
        })
    </script>

    {{-- Rupiah Input --}}
    <script src="{{ asset('js/rupiah-input.js') }}"></script>

    {{-- Dependent Door Dropdown --}}
    {{-- <script>
        function updateDoorDropdown() {
            var buildingDropdown = document.getElementById("jenis_properti");
            var doorDropdown = document.getElementById("jumlah_bilik");

            // Clear existing options
            doorDropdown.innerHTML = '<option disabled value="">Bilik</option>';

            // Get the selected building value
            var selectedBuilding = buildingDropdown.value;

            // Generate options for the selected building

            var dataProperti = <?php echo json_encode($dataProperti); ?>;
            console.log(dataProperti);

            var dataBilik = <?php echo json_encode($dataBilik); ?>;

            dataProperti.forEach(element => {
                if (selectedBuilding === element.properti_id) {
                    for (var i = 1; i <= element.jumlah_bilik; i++) {
                    var shouldSkip = false;
                    dataBilik.forEach(bilik => {
                        if (selectedBuilding === element.properti_id && i === bilik.no_bilik) {
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
    </script> --}}
@stop
