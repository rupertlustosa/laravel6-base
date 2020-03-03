<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>{{ $label }}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"
                   data-toggle="tooltip" data-placement="bottom" title="Navega para o início">Início</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>
                    <a href="{{ route('roles.index') }}"
                       data-toggle="tooltip" data-placement="bottom" title="Navega para a listagem">{{ $label }}</a>
                </strong>
            </li>
        </ol>
    </div>
    <div v-if="{{ config('app.showButtonsInModuleNavBar') ? 'true' : 'false' }}" class="col-lg-4">
        <div class="btn-group pull-right" style="margin-top: 30px;">
            <a class="btn btn-default" href="{{ route('roles.index') }}"
               data-toggle="tooltip" data-placement="bottom" title="Navega para a listagem">
                <i class="fa fa-list-ul"></i>
                Listar
            </a>
            @if(Auth::user()->can('create', \App\Models\Role::class))
                <a class="btn btn-primary" id="ln_adicionar" href="{{ route('roles.create') }}"
                   data-toggle="tooltip" data-placement="bottom" title="Cria um novo registro">
                    <i class="fa fa-plus-circle"></i> Novo
                </a>
            @endif
        </div>
    </div>
</div>
