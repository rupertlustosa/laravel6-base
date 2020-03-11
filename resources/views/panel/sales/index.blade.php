@extends('panel._layouts.panel')

@section('_titulo_pagina_', 'Lista de '.$label)

@section('content')

    @include('panel.sales.nav')

    @php

        //$_placeholder_ = "Localize por ''";
    @endphp

    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">

                <div class="ibox">

                    <div class="ibox-title">

                        <h5 v-if="{{ config('app.showButtonsInModuleNavBar') ? 'true' : 'false' }}">
                            Lista de vendas
                        </h5>

                        <div v-if="{{ config('app.showButtonsInModuleNavBar') ? 'false' : 'true' }}" class="ibox-tools">
                            @if(Auth::user()->can('create', \App\Models\Sale::class))
                                <a href="{{ route('sales.create') }}" class="btn btn-primary {{--btn-xs--}}">
                                    <i class="fa fa-plus"></i> Cadastrar
                                </a>
                            @endif
                        </div>
                    </div>

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
                                                    Cliente: {{ $item->document_number }}
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
@endsection

@section('styles')

@endsection

@section('scripts')

    <script type="text/javascript">
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
@endsection
