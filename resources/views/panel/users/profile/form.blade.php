@extends('panel._layouts.panel')

@section('_titulo_pagina_', 'Edição do meu perfil')

@section('content')

    @include('panel.users.profile.nav')

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
                              enctype="multipart/form-data"
                              action="{{ route('users.profileUpdate', $item->id) }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}

                        <!-- inicio dos campos -->

                            <div class="form-row">
                                <div class="form-group col-md-6 @if ($errors->has('name')) has-error @endif">
                                    <label for="name">Nome</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ old('name', (isset($item) ? $item->name : '')) }}">
                                    {!! $errors->first('name','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div class="form-group col-md-6 @if ($errors->has('email')) has-error @endif">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                           value="{{ old('email', (isset($item) ? $item->email : '')) }}">
                                    {!! $errors->first('email','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div class="form-group col-md-6 @if ($errors->has('password')) has-error @endif">
                                    <label for="password">Senha</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                           value="{{ old('password') }}">
                                    {!! $errors->first('password','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div
                                    class="form-group col-md-6 @if ($errors->has('password_confirmation')) has-error @endif">
                                    <label for="password_confirmation">Confirmar Senha</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control"
                                           value="">
                                    {!! $errors->first('password_confirmation','<span class="help-block m-b-none">:message</span>') !!}
                                </div>


                            </div>

                            <!-- fim dos campos -->

                            <input id="routeTo" name="routeTo" type="hidden"
                                   value="{{ old('routeTo', 'users.profile') }}">
                            <button class="btn btn-primary" id="bt_salvar" type="submit">
                                <i class="fa fa-save"></i>
                                {{ isset($item) ? 'Salvar Alterações' : 'Salvar' }}
                            </button>

                            <!-- FIM -->
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('styles')

@endsection


@section('scripts')
    @include('panel._assets.scripts-form')
    {!! $validator->selector('#frm_save') !!}
@endsection
