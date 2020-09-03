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

        fieldset.scheduler-border {
            border: 1px solid #333333 !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0 0 0 0 #000;
            box-shadow: 0 0 0 0 #000;
        }

        fieldset.scheduler-border-color-2 {
            border: 1px solid #41cbd8 !important;
        }

        legend.scheduler-border {
            width: inherit; /* Or auto */
            padding: 0 10px; /* To give a bit of padding on the left and right */
            border-bottom: none;
            color: #333333 !important;
        }

        legend.scheduler-border h4 {
            color: #333333 !important;
        }

        legend {
            font-size: 13px !important;
            font-weight: 600;
        }
    </style>
</head>
<body class="light-skin_ fixed-nav fixed-nav-basic fixed-sidebar">
<div id="app">
    <!-- Wrapper-->
    <div id="wrapper">

        @include('panel._layouts.main-navigation-side')

        <div id="page-wrapper" class="white-bg">
            @include('panel._layouts.top-navbar')

            @yield('content')

            @include('panel._layouts.footer')
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
<script src="{{ mix('/js/functions.js') }}"></script>

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
