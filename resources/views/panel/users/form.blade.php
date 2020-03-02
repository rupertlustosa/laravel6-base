@extends('panel._layouts.panel')

@section('_titulo_pagina_', (isset($item) ? 'Edição' : 'Cadastro') . ' de '.$label)

@section('content')

    @include('panel.users.nav')

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@yield('_titulo_pagina_')</h5>
                    </div>
                    <div class="ibox-content">

                        @if (Auth::user()->is_dev && count($errors) > 0)
                            <div class="alert alert-danger dev-mod">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" class="form-horizontal" id="frm_save" autocomplete="off"
                              action="{{ isset($item) ? route('users.update', $item->id) : route('users.store') }}">
                        {{ method_field(isset($item) ? 'PUT' : 'POST') }}
                        {{ csrf_field() }}

                        <!-- inicio dos campos -->

                            <div class="form-row">
                                <div class="form-group col-md-12 @if ($errors->has('name')) has-error @endif">
                                    <label for="name">Nome</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                    		value="{{ old('name', (isset($item) ? $item->name : '')) }}">
                                    {!! $errors->first('name','<span class="help-block m-b-none">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 @if ($errors->has('image')) has-error @endif">
                                    <label for="image">Imagem</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    {!! $errors->first('image','<span class="help-block m-b-none">:message</span>') !!}
                                    @if(isset($item) && $item->image)
                                        <br/>
                                        <label> <input type="checkbox" value="1" name="delete_image">
                                            Remover image?
                                        </label>
                                    @endif
                                </div>
                            </div>

                            <br>
                            <fieldset class="scheduler-border mt-2">
                                <legend align="left">Dados de Acesso:</legend>
                                <div class="form-row">
                                    <div class="form-group col-md-12 @if ($errors->has('email')) has-error @endif">
                                        <label for="email">E-mail *</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                               value="{{ old('email', (isset($item) ? $item->email : '')) }}">
                                        {!! $errors->first('email','<span class="help-block m-b-none">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 @if ($errors->has('password')) has-error @endif">
                                        <label for="password">Senha {{ isset($item) ? '' : ' *' }}</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                               value="">
                                        {!! $errors->first('password','<span class="help-block m-b-none">:message</span>') !!}
                                    </div>
                                    <div
                                        class="form-group col-md-6 @if ($errors->has('password_confirmation')) has-error @endif">
                                        <label for="password_confirmation">Confirmar
                                            Senha {{ isset($item) ? '' : ' *' }}</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                               class="form-control"
                                               value="">
                                        {!! $errors->first('password_confirmation','<span class="help-block m-b-none">:message</span>') !!}
                                    </div>
                                </div>


                                @php
                                    $rolesOld = old('roles') ?? [];
                                @endphp
                                <div class="form-row">
                                    <div class="form-group col-md-12 @if ($errors->has('roles')) has-error @endif">
                                        <label for="columnist_id">Qual o nível dessa pessoa?</label>
                                        <select class="form-control select2" multiple name="roles[]" id="roles">
                                            <option value="">Selecione abaixo</option>
                                            @foreach($roleOptions as $i => $v)
                                                @php
                                                    $selected = '';
                                                    if(in_array($i, $rolesOld)) {

                                                        $selected = 'selected';
                                                    }
                                                    else if(isset($item)) {

                                                        $rolesDatabase = isset($item) ? $item->roles->pluck('id')->toArray() : [];

                                                        if(in_array($i, $rolesDatabase)) {

                                                            $selected = 'selected';
                                                        }
                                                    }
                                                @endphp
                                                <option
                                                    value="{{ $i }}" {{ $selected }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('roles','<span class="help-block m-b-none">:message</span>') !!}
                                    </div>
                                </div>
                            </fieldset>

                        <!-- fim dos campos -->

                            <input id="routeTo" name="routeTo" type="hidden" value="{{ old('routeTo', 'index') }}">
                            <button class="btn btn-primary" id="bt_salvar" type="submit"
                                    data-toggle="tooltip" data-placement="bottom" title="Salva os dados atuais">
                                <i class="fa fa-save"></i>
                                {{ isset($item) ? 'Salvar Alterações' : 'Salvar' }}
                            </button>

                            @if(!isset($item))
                                <button class="btn btn-default" id="bt_salvar_adicionar" type="submit"
                                        data-toggle="tooltip" data-placement="bottom" title="Salva e continua na tela de cadastro">
                                    <i class="fa fa-save"></i>
                                    Salvar e adicionar novo
                                </button>
                            @else
                                <a class="btn btn-default" id="ln_listar_form" href="{{ route('users.index') }}"
                                   data-toggle="tooltip" data-placement="bottom" title="Navega para a listagem">
                                    <i class="fa fa-list-ul"></i>
                                    Listar
                                </a>
                        @endif
                        <!-- FIM -->
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('styles')
    <style>
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            width: inherit; /* Or auto */
            padding: 0 10px; /* To give a bit of padding on the left and right */
            border-bottom: none;
        }

        legend {
            font-size: 12px !important;
        }
    </style>
@endsection


@section('scripts')
    @include('panel._assets.scripts-form')
    {!! $validator->selector('#frm_save') !!}
@endsection
