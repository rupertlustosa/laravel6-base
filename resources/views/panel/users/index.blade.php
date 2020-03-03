@extends('panel._layouts.panel')

@section('_titulo_pagina_', 'Lista de '.$label)

@section('content')

    @include('panel.users.nav')

    @php

        $_placeholder_ = "Localize por 'Nome ou e-mail'";
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
                            @if(Auth::user()->can('create', \App\Models\User::class))
                                <a href="{{ route('users.create') }}" class="btn btn-primary {{--btn-xs--}}">
                                    <i class="fa fa-plus"></i> Cadastrar
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="ibox-content">

                        <div class="m-b-lg">
                            <form method="get" id="frm_search" action="{{ route('users.index') }}">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="search">Localizar</label>
                                        <input type="text" id="search" name="search" class="form-control"
                                               value="{{ request('search') }}"
                                               placeholder="{{ isset($_placeholder_) ? $_placeholder_ : 'Digite algo para realizar sua busca' }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="type">Perfil</label>
                                        <select id="role_id" name="role_id" class="form-control">
                                            <option value="">Todos</option>
                                            @foreach (\App\Models\Role::all() as $role)
                                                <option value="{{ $role->id }}"
                                                        @if ($role->id == request('role_id', (isset($item) ? $item->role_id : '')))
                                                        selected="selected"
                                                    @endif
                                                >{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">

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

                            @if($data->count())

                                <table class="table table-striped table-bordered table-hover">

                                    <thead>
                                    <tr>

                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Perfis</th>
                                        <th class="hidden-xs hidden-sm" style="width: 150px;">Criado em</th>
                                        <th style="width: 100px; text-align: center">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if($data->count())
                                        @foreach($data as $item)
                                            <tr id="tr-{{ $item->id }}">

                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>
                                                    @foreach ($item->roles->pluck('name') as $role)
                                                        <span class="label label-primary">{{ $role }}</span>
                                                    @endforeach
                                                </td>
                                                <td class="hidden-xs hidden-sm"
                                                    data-toggle="tooltip" data-placement="bottom" title="{!! implode("\r\n", $item->creationData()) !!}">
                                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                                </td>
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
                                                               href="{{ route('users.edit', [$item->id]) }}">
                                                                <i class="fa fa-pencil fa-fw"></i> Editar
                                                            </a>
                                                            <link-destroy-component
                                                                line-id="{{ 'tr-'.$item->id }}"
                                                                link="{{ route('users.destroy', [$item->id]) }}">
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
                                    <a class="alert-link" href="{{ route('users.index') }}">
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
