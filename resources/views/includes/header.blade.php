<style>
    .logo-name {
        margin-left: 95px !important;
    }

    @media screen and (max-width: 480px) {
        .cnt {
            padding-left: 75px;
        }

        .rmv-name {
            display: none !important;
        }

        .logo-name {
            margin-left: 80px !important;
            margin-right: 80px;
        }
    }
</style>

<div class="container d-flex justify-content-center cnt">
    <div class="">
        <button class="my-0 btn btn-sm rounded-2 mx-4 my-auto blinking-border rounded-circle border-3"
            style="font-size: 18px;background-color:white" id="menu-btn"><i class="fa-solid fa-bars fa-lg"
                style="color: #2185D5"></i></button>
    </div>

    <a href="@if (Auth::check() && Auth::user()->role === 'Admin') {{ route('radmin', ['id' => Auth::user()->id]) }} @elseif(Auth::check() && Auth::user()->role === 'Penghuni') {{ route('rpenghuni', ['id' => Auth::user()->id]) }} @endif"
        class="d-flex align-items-center text-dark text-decoration-none mx-auto">
        <h3 class="fw-bold logo-name" style="color: white">RENTIFY</h3>
    </a> {{-- THIS <A> --}}

    <a class="dropdown-toggle hide-dropdown-arrow custom-a-unset mx-2 my-0" href="#" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <div class="d-flex align-items-center ">
            <div class="d-flex align-items-end flex-column rmv-name">
                <p class="my-0  fw-bold" style="color: white">Hi, {{ Auth::user()->name }}</p>
                <p class="my-0 " style="color: white;font-size: 14px;">{{ Auth::user()->role }}</p>
            </div>
            <i class="fas fa-user-circle py-1 mx-3" title="Profile" style="font-size: 36px; color:white"></i>
        </div>
    </a>


    <ul class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="navbarDropdown">
        @if (Auth::user()->role == 'Admin')
            <li><a class="dropdown-item" href="{{ route('rProfileAdmin', ['id' => Auth::user()->id]) }}">Profile</a>
            </li>
        @elseif (Auth::user()->role == 'Penghuni')
            <li><a class="dropdown-item" href="{{ route('rProfilePenghuni', ['id' => Auth::user()->id]) }}">Profile</a>
            </li>
        @endif
        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
    </ul>
</div>
