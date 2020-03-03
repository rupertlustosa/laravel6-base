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
