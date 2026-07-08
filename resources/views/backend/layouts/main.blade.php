<!DOCTYPE html>
<html lang="zxx">

<!-- Mirrored from templates.hibootstrap.com/farol/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 28 Jan 2024 11:59:27 GMT -->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ asset('backend/assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/sidebar-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/rangeslider.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/sweetalert.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('backend/assets/images/logo-lampu-jalan.png') }}">

    <title>Sistem Rekomendasi Lampu Jalan</title>
</head>

<body>

    {{-- <div class="preloader" id="preloader">
        <div class="preloader">
            <div class="waviy position-relative">
                <span class="d-inline-block">G</span>
                <span class="d-inline-block">E</span>
                <span class="d-inline-block">O</span>
                <span class="d-inline-block">W</span>
                <span class="d-inline-block">I</span>
                <span class="d-inline-block">S</span>
                <span class="d-inline-block">A</span>
                <span class="d-inline-block">T</span>
                <span class="d-inline-block">A</span>
            </div>
        </div>
    </div> --}}

    @include('backend.layouts.sidebar')

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            @include('backend.layouts.header')

            @yield('content')
   
             @include('backend.layouts.footer')

        </div>
    </div>
   
    


    <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('backend/assets/js/dragdrop.js') }}"></script>
    <script src="{{ asset('backend/assets/js/rangeslider.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/sweetalert.js') }}"></script>
    <script src="{{ asset('backend/assets/js/quill.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/data-table.js') }}"></script>
    <script src="{{ asset('backend/assets/js/prism.js') }}"></script>
    <script src="{{ asset('backend/assets/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/amcharts.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom/ecommerce-chart.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom/custom.js') }}"></script>
</body>

<!-- Mirrored from templates.hibootstrap.com/farol/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 28 Jan 2024 11:59:45 GMT -->

</html>
