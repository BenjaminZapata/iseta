<div class="header-avatar">

    <!-- IZQUIERDA: Título -->
    <div>
        <h2 style="font-size: 1.8rem; font-weight: bold; margin: 0;">
            {{ $tituloSeccion ?? 'GESTIÓN' }}
        </h2>
    </div>

    <!-- DERECHA: Bienvenida + Botón Importar + Avatar -->
    <div style="display: flex; align-items: center; gap: 1rem; position: relative; padding-right: 30px;">
        <span style="font-size: 1rem; text-transform: none;">¡Bienvenido, Usuario!</span>

        <!-- Notificaciones -->
        <div>
            <livewire:notificaciones />
        </div>

        <!-- Avatar con dropdown -->
        <div style="position: relative;">
            <div id="avatar-toggle" style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;"
                onclick="toggleUserMenu()">
                <img src="{{ asset('img/user-icon.png') }}" alt="Regente" title="Usuario"
                    style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid white; background-color: white;" />
                <i id="avatar-arrow" class="ti ti-chevron-down"
                    style="color: white; font-size: 1rem; position: relative; top: -2px; transition: transform 0.2s ease;"></i>
            </div>

            <!-- Dropdown (oculto por defecto) -->
            <div id="user-menu" class="header-dropdown-avatar">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <li>
                        <a href="{{ route('admin.config.modoseguro') }}" class="header-avatar-lista">
                            <i class="ti ti-shield-lock" style="font-size: 1.3em; margin-right: 8px;"></i>
                            <span>{{ $config['modo_seguro'] ? 'Desactivar modo seguro' : 'Activar modo seguro' }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.importar.index') }}" class="header-avatar-lista">
                            <i class="ti ti-file-upload" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Importar
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
