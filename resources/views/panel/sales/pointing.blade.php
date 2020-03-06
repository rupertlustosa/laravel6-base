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
            <div class="col-lg-12">

                <div class="ibox">

                    <div class="ibox-content">

                        <div class="m-b-lg">
                            <form method="get" id="frm_search" action="{{ route('sales.index') }}">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="active">Sincronizadas?</label>
                                        <select name="synced" id="synced" class="form-control">
                                            <option value="">Todas</option>
                                            @foreach(config('enums.synced') as $i => $v)
                                                <option
                                                    value="{{ $i }}" {{ request('synced') == $i ? 'selected' : '' }}>{{ $v }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-7">

                                    </div>
                                    <div class="form-group col-sm-2 text-right">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary form-control" id="btn_search"
                                                data-toggle="tooltip" data-placement="bottom" title="Realiza a busca">
                                            <i class="fa fa-search"></i> Pesquisar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <div id="notification"></div>
                            @if($data->count())

                                <table class="table table-striped table-bordered table-hover">

                                    <thead>
                                    <tr>

                                        <th>Venda</th>
                                        <th>Data</th>
                                        <th>Valor</th>
                                        <th>Produto</th>
                                        <th>Bomba / Bico</th>
                                        <th>Atendente / Cliente</th>
                                        <th>Sincronizado em:</th>
                                        {{--<th class="hidden-xs hidden-sm" style="width: 150px;">Criado em</th>--}}
                                        {{--<th style="width: 100px; text-align: center">Ações</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if($data->count())
                                        @foreach($data as $item)
                                            <tr id="tr-{{ $item->id }}">

                                                <td>{{ $item->sale }}</td>
                                                <td>{{ $item->date ? $item->date->format('d/m/Y H:i:s') : '' }}</td>
                                                <td>{{ getCurrency($item->value) }}</td>
                                                <td>
                                                    Identificação: {{ $item->item_identification }}<br>
                                                    Produto: {{ $item->item_name }}<br>
                                                    <small>
                                                        Quantidade: {{ $item->item_quantity }}<br>
                                                        Valor unitário: {{ getCurrency($item->item_unit_price) }}
                                                    </small>
                                                </td>
                                                <td>
                                                    Bomba: {{ $item->fuel_pump }}<br>
                                                    Bico: {{ $item->fuel_pump_nozzle }}
                                                </td>
                                                <td>
                                                    Atendente: {{ $item->attendant }}<br>
                                                    Cliente: {{ $item->client }}
                                                </td>
                                                <td>{{ $item->synced_at ? $item->synced_at->format('d/m/Y H:i:s') : '' }}</td>
                                                {{--<td class="hidden-xs hidden-sm"
                                                    data-toggle="tooltip" data-placement="bottom" title="{!! implode("\r\n", $item->creationData()) !!}">{{ $item->created_at->format('d/m/Y H:i') }}</td>--}}
                                                {{--<td style="text-align: center">
                                                    <div class="btn-group" role="group">
                                                        <button id="btnGroupDrop1" type="button"
                                                                class="btn btn-default dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            Ações
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                            <a class="dropdown-item"
                                                               href="{{ route('sales.edit', [$item->id]) }}">
                                                                <i class="fa fa-pencil fa-fw"></i> Editar
                                                            </a>
                                                            <link-destroy-component
                                                                line-id="{{ 'tr-'.$item->id }}"
                                                                link="{{ route('sales.destroy', [$item->id]) }}">
                                                            </link-destroy-component>
                                                        </div>
                                                    </div>
                                                </td>--}}
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>

                                @include('panel._assets.paginate')

                            @else
                                <div class="alert alert-danger">
                                    Não temos nada para exibir. Caso tenha realizado uma busca você pode realizar
                                    uma nova com outros termos ou
                                    <a class="alert-link" href="{{ route('sales.index') }}">
                                        limpar sua pesquisa.
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>

<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{ mix('/js/custom-masks.js') }}"></script>
<script src="{{ mix('/js/moment.js') }}"></script>
{{--<script src="{{ mix('/js/blockUI.js') }}"></script>--}}
<script src="{{ mix('/js/functions.js') }}"></script>

{{--<script src="{{ mix('/js/custom-select2.js') }}"></script>
<script src="{{ mix('/js/custom-datepicker.js') }}"></script>
<script src="{{ mix('/js/custom-datetimepicker.js') }}"></script>--}}


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
    window.Echo.channel('sale-event')
        .listen('SaleEvent', (data) => {
            i++;
            $("#notification").append('<div class="alert alert-success">' + i + '.' + data.title + '</div>');
            console.log('sale-event ' + i)
        });

    window.Echo.channel('sale-event')
        .listen('SaleEvent', (e) => {
            console.log(e);
        });

</script>
</body>
</html>
