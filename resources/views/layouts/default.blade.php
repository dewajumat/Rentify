<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-image: url('{{ asset('site-image/background.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
        }

        @media screen and (max-width: 480px) {
            header {
                width: 25rem!important;
            }
        }
    </style>
</head>

<body class="">
    <nav class="navbar navbar-expand d-flex flex-column align-item-start" id="sidebar">
        @include('includes.navbar')
    </nav>
    <section class="my-container pst">
        <div class="sticky-top header" style="background-color: #2185D5 " id="main-header">
            <header class="d-flex flex-wrap justify-content-center py-3 mb-4 ">
                @include('includes.header')
            </header>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="https://kit.fontawesome.com/3651899871.js" crossorigin="anonymous"></script>
</body>

</html>
