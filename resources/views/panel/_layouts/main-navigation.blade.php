@php
    $userIsDev = Auth::user()->is_dev;
    $userIsAdmin = true
@endphp

<div class="row border-bottom white-bg">
    <nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">

        <a href="{{ route('dashboard') }}" class="navbar-brand text-center">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-reorder"></i>
        </button>

        <!--</div>-->
        <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav mr-auto">

                <li class="{{ isActiveRoute('dashboard') }}">
                    <a aria-expanded="false" role="button" href="{{ route('dashboard') }}">
                        <i class="fa fa-home"></i>
                        <span class="nav-label">Início</span>
                    </a>
                </li>
                <li class="{{ isActiveRoute('roles.*') }}">
                    <a aria-expanded="false" role="button" href="{{ route('roles.index') }}">
                        <i class="fa fa-image"></i>
                        <span class="nav-label">Perfis</span>
                    </a>
                </li>
                <li class="{{ isActiveRoute('users.*') }}">
                    <a aria-expanded="false" role="button" href="{{ route('users.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Usuários</span>
                    </a>
                </li>
                <li class="dropdown {{ isActiveRoute('sales.*') }}">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle"
                       data-toggle="dropdown">
                        <i class="fa fa-th-list"></i>
                        Vendas
                    </a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="{{ route('sales.index') }}">Vendas</a></li>
                        <li><a href="{{ route('sales.synced') }}">Sincronizadas</a></li>
                        <li><a href="{{ route('sales.not-synced') }}">Pendentes de Sincronização</a></li>
                    </ul>
                </li>
                {{--<li class="{{ isActiveRoute('sales.*') }}">
                    <a aria-expanded="false" role="button" href="{{ route('sales.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Vendas (todas)</span>
                    </a>
                </li>
                <li class="{{ isActiveRoute('sales.*') }}">
                    <a aria-expanded="false" role="button" href="{{ route('sales.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Vendas (Sincronizadas)</span>
                    </a>
                </li>
                <li class="{{ isActiveRoute('sales.*') }}">
                    <a aria-expanded="false" role="button" href="{{ route('sales.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Vendas (Não sincronizadas)</span>
                    </a>
                </li>--}}
                <li class="{{ isActiveRoute('pointing.*') }}">
                    <a aria-expanded="false" role="button" href="{{ route('pointing.index') }}" target="_blank">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Pontuar</span>
                    </a>
                </li>
            </ul>

            <form name="frm_new_users_notifications" id="frm_new_users_notifications">
                {{ method_field('POST') }}
                {{ csrf_field() }}
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="{{ route('users.profile') }}">
                            <i class="fa fa-user"></i>Perfil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> Sair
                        </a>
                    </li>
                </ul>
            </form>
        </div>
    </nav>
</div>
