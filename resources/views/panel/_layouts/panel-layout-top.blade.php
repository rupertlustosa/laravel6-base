<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - @yield('_titulo_pagina_') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{!! mix('css/app.css') !!}"/>

    @yield('styles')
    <style>
        /*.ms-choice {
            width: 99% !important;
        }

        .ms-choice > span {
            top: 6px !important;
            left: 15px !important;
        }

        .form-group label {
            font-weight: 600;
        }*/

        .form-control, .single-line {
            border: 1px solid rgb(223, 225, 229);
        }

        .select2-container--bootstrap4 .select2-dropdown {
            z-index: 9999;
        }

        .navbar ul .dropdown-menu {
            min-width: 13rem !important;
        }

        .select2-selection {
            padding: 0;
        }

        @media only screen and (max-width: 768px) {
            .top-navigation .wrapper.wrapper-content {
                padding: 10px 0;
            }
        }
    </style>
</head>
<body class="top-navigation skin-1">
<div id="app">
    <!-- Wrapper-->
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">

        @if(!request()->has('iframe'))
            @include('panel._layouts.main-navigation-top')
        @endif

        <!-- Main view  -->
            <div id="topo-tela"></div>
        @yield('content')

        <!-- Footer -->
            @if(!request()->has('iframe'))
                @include('panel._layouts.footer')
            @endif
        </div>
        <!-- End page wrapper-->
    </div>
</div>
<!-- End wrapper-->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>

@section('scripts')
@show
<script>
    @if (Session::has('message'))
    showMessage('{{ session('messageType') }}', '{{ session('message') }}');
    @endif

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</body>
</html>
