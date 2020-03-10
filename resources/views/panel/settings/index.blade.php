@extends('panel._layouts.panel')

@section('_titulo_pagina_', 'Lista de '.$label)

@section('content')

    @include('panel.settings.nav')

    @php

        //$_placeholder_ = "Localize por ''";
    @endphp

    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">

                <div class="ibox">

                    <div class="ibox-title">

                        <h5 v-if="{{ config('app.showButtonsInModuleNavBar') ? 'true' : 'false' }}">
                            @yield('_titulo_pagina_')
                        </h5>

                        <div v-if="{{ config('app.showButtonsInModuleNavBar') ? 'false' : 'true' }}" class="ibox-tools">
                            @if(Auth::user()->can('create', \App\Models\Setting::class))
                                <a href="{{ route('settings.create') }}" class="btn btn-primary {{--btn-xs--}}">
                                    <i class="fa fa-plus"></i> Cadastrar
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="ibox-content">

                        <div class="table-responsive">

                            @if($data->count())

                                <table class="table table-striped table-bordered table-hover">

                                    <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Chave</th>
                                        <th>Valor</th>
                                        <th class="hidden-xs hidden-sm" style="width: 150px;">Criado em</th>
                                        <th style="width: 100px; text-align: center">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if($data->count())
                                        @foreach($data as $item)
                                            <tr id="tr-{{ $item->id }}">

                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->key }}</td>
                                                <td>{{ $item->value }}</td>
                                                <td class="hidden-xs hidden-sm"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="{!! implode("\r\n", $item->creationData()) !!}">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                <td style="text-align: center">
                                                    <div class="btn-group" role="group">
                                                        <button id="btnGroupDrop1" type="button"
                                                                class="btn btn-default dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            Ações
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                            <a class="dropdown-item"
                                                               href="{{ route('settings.edit', [$item->id]) }}">
                                                                <i class="fa fa-pencil fa-fw"></i> Editar
                                                            </a>
                                                            <link-destroy-component
                                                                line-id="{{ 'tr-'.$item->id }}"
                                                                link="{{ route('settings.destroy', [$item->id]) }}">
                                                            </link-destroy-component>
                                                        </div>
                                                    </div>
                                                </td>
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
                                    <a class="alert-link" href="{{ route('settings.index') }}">
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

@endsection
