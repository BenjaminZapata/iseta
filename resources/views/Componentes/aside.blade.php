<aside id="sidebar" onmouseover="this.style.width='16rem'" onmouseout="this.style.width='4rem'">
    <div class="sidebar-header">
        {{-- Logo colapsado (chico) --}}
        <img src="{{ asset('img/logo-mini.png') }}" alt="Logo Mini" class="logo-mini">

        {{-- Logo expandido (grande) --}}
        <img src="{{ asset('img/logo.png') }}" alt="Logo Completo" class="logo-full">
    </div>

    <ul>
        <li class="dropdown">
            <a href="#" onclick="toggleDropdown(event)">
                <i class="ti ti-user"></i>
                <span>Alumnos</span>
                <i class="ti ti-chevron-down arrow"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.alumnos.index') }}">Listado de Alumnos</a>
                </li>
                <li>
                    <a href="{{ route('admin.inscriptos.index') }}">Inscriptos</a>
                </li>
            </ul>
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
            <a href="{{ route('admin.asignaturas.index') }}">
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
    </ul>
</aside>

<script>
    function toggleDropdown(event) {
        event.preventDefault();
        const li = event.currentTarget.closest('.dropdown');
        li.classList.toggle('open');
    }
</script>
