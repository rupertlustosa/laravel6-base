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
