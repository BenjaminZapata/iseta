<aside id="sidebar" class="sidebar" onmouseover="expandSidebar()" onmouseout="handleSidebarMouseOut(event)"
    aria-label="Barra lateral de navegación">
    <div class="sidebar__header">
        {{-- Logos --}}
        <img src="{{ asset('img/logo-mini.png') }}" alt="Logo Mini" class="sidebar__logo--mini">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Completo" class="sidebar__logo--full">
    </div>

    <nav class="sidebar__nav" role="navigation">
        <ul class="sidebar__list">
            <li class="sidebar__item {{ request()->routeIs('admin.alumnos.*') ? 'is-active' : '' }}">
                <a class="sidebar__link" href="{{ route('admin.alumnos.index') }}">
                    <i class="ti ti-user sidebar__icon"></i>
                    <span class="sidebar__text">Alumnos</span>
                </a>
            </li>

            <li class="sidebar__item {{ request()->routeIs('admin.profesores.*') ? 'is-active' : '' }}">
                <a class="sidebar__link" href="{{ route('admin.profesores.index') }}">
                    <i class="ti ti-users sidebar__icon"></i>
                    <span class="sidebar__text">Profesores</span>
                </a>
            </li>

            <li class="sidebar__item {{ request()->routeIs('admin.carreras.*') ? 'is-active' : '' }}">
                <a class="sidebar__link" href="{{ route('admin.carreras.index') }}">
                    <i class="ti ti-folders sidebar__icon"></i>
                    <span class="sidebar__text">Carreras</span>
                </a>
            </li>

            <li class="sidebar__item {{ request()->routeIs('admin.asignaturas.*') ? 'is-active' : '' }}">
                <a class="sidebar__link" href="{{ route('admin.asignaturas.index') }}">
                    <i class="ti ti-notes sidebar__icon"></i>
                    <span class="sidebar__text">Asignaturas</span>
                </a>
            </li>

            <li class="sidebar__item {{ request()->routeIs('admin.mesas.*') ? 'is-active' : '' }}">
                <a class="sidebar__link" href="{{ route('admin.mesas.index') }}">
                    <i class="ti ti-address-book sidebar__icon"></i>
                    <span class="sidebar__text">Mesas</span>
                </a>
            </li>

            <li class="sidebar__item {{ request()->routeIs('admin.cursadas.*') ? 'is-active' : '' }}">
                <a class="sidebar__link" href="{{ route('admin.cursadas.index') }}">
                    <i class="ti ti-books sidebar__icon"></i>
                    <span class="sidebar__text">Cursadas</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<script>
    function expandSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.add('is-expanded');
    }

    function handleSidebarMouseOut(event) {
        const sidebar = document.getElementById('sidebar');
        const toElement = event.relatedTarget;
        if (sidebar && toElement && sidebar.contains(toElement)) return;
        collapseSidebar();
    }

    function collapseSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.remove('is-expanded');
    }
</script>
