<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - @yield('_titulo_pagina_') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{!! mix('css/app.css') !!}"/>
    <link rel="stylesheet" href="{!! mix('css/theme.css') !!}"/>
    @yield('styles')
    <style>
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

    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            {{--<div class="row justify-content-md-center">
                <div class="col-md-12">

                </div>
            </div>--}}

            <div class="col-lg-12">

                <div class="ibox">

                    <div class="ibox-content">

                        <div class="m-b-lg">
                            <nozzle-list-component></nozzle-list-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{--<script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>--}}

<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{ mix('/js/custom-masks.js') }}"></script>
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
    var i = 0;
    /*window.Echo.channel('sale-event')
        .listen('SaleEvent', (data) => {
            i++;
            $("#notification").append('<div class="alert alert-success">' + i + '.' + data.title + '</div>');
            console.log('sale-event ' + i)
        });

    window.Echo.channel('sale-event')
        .listen('SaleEvent', (e) => {
            console.log(e);
        });*/

</script>
</body>
</html>
<script>
    /*import NozzleListComponent from "../../../js/components/sales/NozzleListComponent";

    export default {
        components: {NozzleListComponent}
    }*/
</script>
