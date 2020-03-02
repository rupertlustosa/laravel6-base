<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - @yield('_titulo_pagina_') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{!! mix('css/app.css') !!}"/>
    <style>
        body {
            background-color: #333;
            margin-top: 18px
        }

        .container {
            padding: 33px;
            background-color: #fff
        }

    </style>
    @yield('styles')
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3>
                Gerador de Arquivos - RLustosa
            </h3>
        </div><!--.col -->
    </div><!--./row -->
    @yield('content')
</div><!--./container -->

<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>

@section('scripts')
@show
</body>
</html>
