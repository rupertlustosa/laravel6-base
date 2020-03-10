@extends('panel._layouts.panel')

@section('_titulo_pagina_', (isset($item) ? 'Edição' : 'Cadastro') . ' de '.$label)

@section('content')

    @include('panel.sales.nav')

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
                              action="{{ isset($item) ? route('sales.update', $item->id) : route('sales.store') }}">
                        {{ method_field(isset($item) ? 'PUT' : 'POST') }}
                        {{ csrf_field() }}

                        <!-- inicio dos campos -->

                            <div class="form-row">
                                <div class="form-group col-md-6 @if ($errors->has('sale')) has-error @endif">
                                    <label for="sale">Venda</label>
                                    <input type="text" name="sale" id="sale" class="form-control"
                                    		value="{{ old('sale', (isset($item) ? $item->sale : '')) }}">
                                    {!! $errors->first('sale','<span class="help-block m-b-none">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 @if ($errors->has('date')) has-error @endif">
                                    <label for="date">Data</label>
                                    <div class="input-group date_calendar">
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control mask_date" name="date"
                                                   id="date"
                                                   value="{{ old('date', (isset($item) ? $item->date : '')) }}">
                                        </div>
                                    </div>
                                    {!! $errors->first('date','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div class="form-group col-md-6 @if ($errors->has('value')) has-error @endif">
                                    <label for="value">Valor</label>
                                    <input type="text" name="value" id="value" class="form-control"
                                    		value="{{ old('value', (isset($item) ? $item->value : '')) }}">
                                    {!! $errors->first('value','<span class="help-block m-b-none">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 @if ($errors->has('fuel_pump')) has-error @endif">
                                    <label for="fuel_pump">Bomba</label>
                                    <input type="text" name="fuel_pump" id="fuel_pump" class="form-control"
                                    		value="{{ old('fuel_pump', (isset($item) ? $item->fuel_pump : '')) }}">
                                    {!! $errors->first('fuel_pump','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div class="form-group col-md-6 @if ($errors->has('fuel_pump_nozzle')) has-error @endif">
                                    <label for="fuel_pump_nozzle">Bico</label>
                                    <input type="text" name="fuel_pump_nozzle" id="fuel_pump_nozzle" class="form-control"
                                    		value="{{ old('fuel_pump_nozzle', (isset($item) ? $item->fuel_pump_nozzle : '')) }}">
                                    {!! $errors->first('fuel_pump_nozzle','<span class="help-block m-b-none">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 @if ($errors->has('attendant')) has-error @endif">
                                    <label for="attendant">Atendente</label>
                                    <input type="text" name="attendant" id="attendant" class="form-control"
                                           value="{{ old('attendant', (isset($item) ? $item->attendant : '')) }}">
                                    {!! $errors->first('attendant','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div class="form-group col-md-6 @if ($errors->has('document_number')) has-error @endif">
                                    <label for="document_number">Cliente</label>
                                    <input type="text" name="document_number" id="document_number" class="form-control"
                                           value="{{ old('document_number', (isset($item) ? $item->document_number : '')) }}">
                                    {!! $errors->first('document_number','<span class="help-block m-b-none">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div
                                    class="form-group col-md-6 @if ($errors->has('item_identification')) has-error @endif">
                                    <label for="item_identification">Identificação do Produto</label>
                                    <input type="text" name="item_identification" id="item_identification"
                                           class="form-control"
                                           value="{{ old('item_identification', (isset($item) ? $item->item_identification : '')) }}">
                                    {!! $errors->first('item_identification','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div class="form-group col-md-6 @if ($errors->has('item_name')) has-error @endif">
                                    <label for="item_name">Nome do Produto</label>
                                    <input type="text" name="item_name" id="item_name" class="form-control"
                                    		value="{{ old('item_name', (isset($item) ? $item->item_name : '')) }}">
                                    {!! $errors->first('item_name','<span class="help-block m-b-none">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 @if ($errors->has('item_quantity')) has-error @endif">
                                    <label for="item_quantity">Quantidade</label>
                                    <input type="text" name="item_quantity" id="item_quantity" class="form-control"
                                    		value="{{ old('item_quantity', (isset($item) ? $item->item_quantity : '')) }}">
                                    {!! $errors->first('item_quantity','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                                <div class="form-group col-md-6 @if ($errors->has('item_unit_price')) has-error @endif">
                                    <label for="item_unit_price">Valor unitário</label>
                                    <input type="text" name="item_unit_price" id="item_unit_price" class="form-control"
                                    		value="{{ old('item_unit_price', (isset($item) ? $item->item_unit_price : '')) }}">
                                    {!! $errors->first('item_unit_price','<span class="help-block m-b-none">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 @if ($errors->has('synced_at')) has-error @endif">
                                    <label for="synced_at">Sincronizado em:</label>
                                    <input type="text" name="synced_at" id="synced_at" class="form-control"
                                    		value="{{ old('synced_at', (isset($item) ? $item->synced_at : '')) }}">
                                    {!! $errors->first('synced_at','<span class="help-block m-b-none">:message</span>') !!}
                                </div>

                            </div>

                            <!-- fim dos campos -->

                            <input id="routeTo" name="routeTo" type="hidden" value="{{ old('routeTo', 'index') }}">
                            <button class="btn btn-primary" id="bt_salvar" type="submit"
                                    data-toggle="tooltip" data-placement="bottom" title="Salva os dados atuais">
                                <i class="fa fa-save"></i>
                                {{ isset($item) ? 'Salvar Alterações' : 'Salvar' }}
                            </button>

                            @if(!isset($item))
                                <button class="btn btn-default" id="bt_salvar_adicionar" type="submit"
                                        data-toggle="tooltip" data-placement="bottom"
                                        title="Salva e continua na tela de cadastro">
                                    <i class="fa fa-save"></i>
                                    Salvar e adicionar novo
                                </button>
                            @else
                                <a class="btn btn-default" id="ln_listar_form" href="{{ route('sales.index') }}"
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

@endsection


@section('scripts')
    @include('panel._assets.scripts-form')
    {!! $validator->selector('#frm_save') !!}
@endsection
