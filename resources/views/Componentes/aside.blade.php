<aside id="sidebar" onmouseover="this.style.width='16rem'" onmouseout="this.style.width='4rem'">
    <div class="sidebar-header">
        {{-- Logo colapsado (chico) --}}
        <img src="{{ asset('img/logo-mini.png') }}" alt="Logo Mini" class="logo-mini">

        {{-- Logo expandido (grande) --}}
        <img src="{{ asset('img/logo.png') }}" alt="Logo Completo" class="logo-full">
    </div>

    <ul>

        <li>
            <a href="{{ route('admin.notificaciones.index') }}">
                <i class="ti ti-bell"></i>
                <span>Notificaciones</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.alumnos.index') }}">
                <i class="ti ti-user"></i>
                <span>Alumnos</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.profesores.index') }}">
                <i class="ti ti-users"></i>
                <span>Profesores</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.carreras.index') }}">
                <i class="ti ti-folders"></i>
                <span>Carreras</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="ti ti-notes"></i>
                <span>Asignaturas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.mesas.index') }}">
                <i class="ti ti-address-book"></i>
                <span>Mesas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.cursadas.index') }}">
                <i class="ti ti-books"></i>
                <span>Cursadas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.inscriptos.index') }}">
                <i class="ti ti-file-invoice"></i>
                <span>Inscriptos</span>
            </a>
        </li>

        <hr>

        <li>
            <a href="{{ route('admin.admins.index') }}">
                <i class="ti ti-user-cog"></i>
                <span>Admins</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.config.index') }}">
                <i class="ti ti-settings"></i>
                <span>Configuración</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.habiles.index') }}">
                <i class="ti ti-calendar-time"></i>
                <span>Días no hábiles</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.config.modoseguro') }}">
                <i class="ti ti-shield-lock"></i>
                <span>{{ $config['modo_seguro'] ? 'Desactivar modo seguro' : 'Activar modo seguro' }}</span>
            </a>
        </li>
        <li>
            <a href="/admin/logout">
                <i class="ti ti-logout"></i>
                <span>Cerrar sesión</span>
            </a>
        </li>
    </ul>
</aside>