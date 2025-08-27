<div class="header-avatar">

    <!-- Renglón 1 -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <!-- IZQUIERDA: Título + Ícono -->
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <h2 style="font-size: 1.8rem; font-weight: bold; margin: 0;">
                {{ $tituloSeccion ?? 'GESTIÓN' }}
            </h2>
            @if (!empty($icono))
            <i class="{{ $icono }}" style="font-size: 1.5rem; color: white;"></i>
            @endif
        </div>

        <!-- DERECHA: Bienvenida + Notificaciones + Avatar -->
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span style="font-size: 1rem;">¡Bienvenido, Usuario!</span>

            <livewire:notificaciones />

            <div style="position: relative;">
                <div id="avatar-toggle" style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;" onclick="toggleUserMenu()">
                    <img src="{{ asset('img/user-icon.png') }}" alt="Regente" title="Usuario"
                        style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid white; background-color: white;" />
                    <i id="avatar-arrow" class="ti ti-chevron-down"
                        style="color: white; font-size: 1rem; position: relative; top: -2px; transition: transform 0.2s ease;"></i>
                </div>

                <div id="user-menu" class="header-dropdown-avatar">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        <!-- Opciones del menú -->
                        <li>
                            <a href="{{ route('admin.config.modoseguro') }}" class="header-avatar-lista">
                                <i class="ti ti-shield-lock" style="font-size: 1.3em; margin-right: 8px;"></i>
                                <span>{{ $config['modo_seguro'] ? 'Desactivar modo seguro' : 'Activar modo seguro' }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.habiles.index') }}" class="header-avatar-lista">
                                <i class="ti ti-calendar-time" style="font-size: 1.3em; margin-right: 8px;"></i>
                                <span>Días no hábiles</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.config.index') }}" class="header-avatar-lista">
                                <i class="ti ti-settings" style="font-size: 1.3em; margin-right: 8px;"></i>
                                Configuración
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.admins.index') }}" class="header-avatar-lista">
                                <i class="ti ti-user-cog" style="font-size: 1.3em; margin-right: 8px;"></i>
                                Administrar usuarios
                            </a>
                        </li>
                        <li>
                            <hr style="margin: 0;">
                        </li>
                        <li>
                            <a href="/admin/logout" class="header-avatar-lista">
                                <i class="ti ti-logout" style="font-size: 1.3em; margin-right: 8px;"></i>
                                <span>Cerrar sesión</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Renglón 2: Breadcrumb alineado a la izquierda -->
    @if (!empty($breadcrumbs))
    <div class="breadcrumb-wrapper">
        <nav class="breadcrumb-nav" aria-label="breadcrumb">
            <ul class="breadcrumb-list">
                @foreach ($breadcrumbs as $index => $breadcrumb)
                <li class="breadcrumb-item">
                    @if ($index !== count($breadcrumbs) - 1)
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                    <span class="separator">›</span>
                    @else
                    <span class="current">{{ $breadcrumb['label'] }}</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
    @endif

</div>