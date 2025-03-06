<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon/favicon-96x96.png') }}">

    <script src="{{ asset('js/app.js') }}"></script>


    {{-- Bootstrap Icon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5.3 -->
    <link rel="stylesheet" href="{{ asset('modules/bootstrap/css/bootstrap.min.css') }}">
    <!-- Vendors styles -->
    <link rel="stylesheet" href="{{ asset('modules/simplebar/dist/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendors/simplebar.css') }}">
    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <!-- <link href="css/examples.css" rel="stylesheet"> -->
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/color-modes.js') }}"></script>
    <script src="{{ asset('modules/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <link href="modules/coreui/chartjs/dist/css/coreui-chartjs.css" rel="stylesheet"> --}}
    <!-- Tailwind CSS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.css" rel="stylesheet"
        integrity="sha384-M6C9anzq7GcT0g1mv0hVorHndQDVZLVBkRVdRb2SsQT7evLamoeztr1ce+tvn+f2" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.js"
        integrity="sha384-k90VzuFAoyBG5No1d5yn30abqlaxr9+LfAPp6pjrd7U3T77blpvmsS8GqS70xcnH" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>

<body>
    <div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
        @include('layouts.nav.sidebar')
        {{-- @if (Auth::user() && Auth::user()->password_changed)
            @include('layouts.nav.sidebar' . Auth::user()->role)
        @endif --}}

    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
        @include('layouts.nav.header')

        <div class="body flex-grow-1">
            <div class="px-4">
                @yield('content')
            </div>
        </div>

        <footer class="footer px-4">
            <div><a href="#">Developer & IT Support</a>
                &copy; 2024 Hermina Lampung.</div>
            <div class="ms-auto">Powered by&nbsp;<a href="#">CoreUI UI Components</a></div>
        </footer>
    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('modules/coreui/coreui/dist/js/coreui.bundle.min.js') }}"></script>
    <script src="{{ asset('modules/simplebar/dist/simplebar.min.js') }}"></script>
    <script>
        const header = document.querySelector('header.header');

        document.addEventListener('scroll', () => {
            if (header) {
                header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
            }
        });
    </script>

    <!-- Plugins and scripts required by this view-->
    {{-- <script src="{{ asset ('modules/chart.js/dist/chart.umd.js') }}"></script> --}}
    <script src="{{ asset('modules/coreui/chartjs/dist/js/coreui-chartjs.js') }}"></script>
    <script src="{{ asset('modules/coreui/utils/dist/umd/index.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    {{-- Sweetalert --}}
    @include('sweetalert::alert')
</body>

</html>
