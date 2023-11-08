<div class="my-5">
    <a href="@if (Auth::check() && Auth::user()->role === 'Admin') {{ route('radmin', ['id' => Auth::user()->id]) }} @elseif(Auth::check() && Auth::user()->role === 'Penghuni') {{ route('rpenghuni', ['id' => Auth::user()->id]) }} @endif"
        class="rounded-circle ">
        <img class=" logo-img rounded-circle" src={{ asset('site-image/bg1.png') }}
            alt="rentify-logossz">
    </a>
</div>

<div class="container">
    <ul class="navbar-nav d-flex flex-column text-nav w-100 custom-nav-list text-decoration: none;">
        @if (Auth::check() && Auth::user()->role === 'Admin')
            <a href="{{ route('radmin', ['id' => Auth::user()->id]) }}"
                class="container border-bottom custom-a-unset py-3 ">
                <div class="row nav-item" style="">
                    <div class="col-10" style="font-size: 18px;color:white">
                        Dashboard
                    </div>
                    <div class="col-2">
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('rpradmin', ['id' => Auth::user()->id]) }}"
                class="container border-bottom custom-a-unset py-3 ">
                <div class="row nav-item" style="">
                    <div class="col-10" style="font-size: 18px;color:white">
                        Properti
                    </div>
                    <div class="col-2">
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('rmadmin', ['id' => Auth::user()->id]) }}"
                class="container border-bottom custom-a-unset py-3 ">
                <div class="row nav-item" style="">
                    <div class="col-10" style="font-size: 18px;color:white">
                        Pemeliharaan
                    </div>
                    <div class="col-2">
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('rKeuanganAdmin', ['id' => Auth::user()->id]) }}"
                class="container border-bottom custom-a-unset py-3 ">
                <div class="row nav-item" style="">
                    <div class="col-10" style="font-size: 18px;color:white">
                        Keuangan
                    </div>
                    <div class="col-2">
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>
            </a>
        @elseif(Auth::check() && Auth::user()->role === 'Penghuni')
            <a href="{{ route('rpenghuni', ['id' => Auth::user()->id]) }}"
                class="container border-bottom custom-a-unset py-3 ">
                <div class="row nav-item" style="">
                    <div class="col-10" style="font-size: 18px;color:white">
                        Dashboard
                    </div>
                    <div class="col-2">
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('rIndexPemeliharaan', ['id' => Auth::user()->id]) }}"
                class="container border-bottom custom-a-unset py-3 ">
                <div class="row nav-item" style="">
                    <div class="col-10" style="font-size: 18px;color:white">
                        Pemeliharaan
                    </div>
                    <div class="col-2">
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('rHistoryPembayaran', ['id' => Auth::user()->id]) }}"
                class="container border-bottom custom-a-unset py-3 ">
                <div class="row nav-item" style="">
                    <div class="col-10" style="font-size: 18px;color:white">
                        Pembayaran
                    </div>
                    <div class="col-2">
                        <i class="fa-solid fa-angle-right"></i>
                    </div>
                </div>
            </a>
        @endif
        <div class="container p-3 " style="position: absolute; bottom: 0;left: 0; right: 0;">
            <div class="container border-top align-items-center">
                <p style="font-size: 16px" class="my-2">&copy; 2023 All rights reserved.</p>
            </div>
        </div>
    </ul>
</div>
