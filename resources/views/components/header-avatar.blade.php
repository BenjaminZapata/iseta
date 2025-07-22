<div class="header-avatar">

    <!-- IZQUIERDA: Título -->
    <div>
        <h2 style="font-size: 1.8rem; font-weight: bold; margin: 0;">
            {{ $tituloSeccion ?? 'GESTIÓN' }}
        </h2>
    </div>
    <!-- DERECHA: Bienvenida + Avatar -->
    <div style="display: flex; align-items: center; gap: 1rem; position: relative; padding-right: 30px;">
        <span style="font-size: 1rem; text-transform: none;">¡Bienvenido, Usuario!</span>

        <!-- Notificaciones -->
        @php
            use App\Models\NotificacionAlumno;

            $notificaciones = NotificacionAlumno::with('alumno')
                ->orderByDesc('fecha')
                ->take(5)
                ->get();

            $cantidadNoLeidas = $notificaciones->where('leido', false)->count();
        @endphp

        <!-- Notificaciones -->
        <div style="position: relative;">
            <div id="notificaciones-toggle" style="cursor: pointer;">
                <i class="ti ti-bell" style="color: white; font-size: 1.2rem;"></i>
                @if($cantidadNoLeidas > 0)
                    <span style="
                            position: absolute;
                            top: -5px;
                            right: -5px;
                            background-color: red;
                            color: white;
                            border-radius: 50%;
                            font-size: 0.7rem;
                            padding: 2px 5px;
                        ">
                        {{ $cantidadNoLeidas }}
                    </span>
                @endif
                <i class="ti ti-chevron-down" style="color: white; font-size: 1rem;"></i>
            </div>

            <!-- Dropdown de notificaciones -->
            <div id="notificaciones-dropdown" style="
        display: none;
        position: absolute;
        top: 30px;
        right: 0;
        background: white;
        color: black;
        width: 320px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 999;
        overflow: hidden;
    ">
                <div style="padding: 10px; font-weight: bold; border-bottom: 1px solid #ddd;">
                    Notificaciones recientes
                </div>
                @forelse($notificaciones as $noti)
                    <div
                        style="padding: 10px; font-size: 0.9rem; border-bottom: 1px solid #f0f0f0; background-color: {{ $noti->leido ? 'white' : '#f9f9f9' }}">
                        <strong>{{ $noti->alumno->apellido }}, {{ $noti->alumno->nombre }}</strong><br>
                        {{ $noti->mensaje }}<br>
                        <small style="color: gray;">{{ \Carbon\Carbon::parse($noti->fecha)->format('d/m/Y') }}</small>
                    </div>
                @empty
                    <div style="padding: 10px;">No hay notificaciones</div>
                @endforelse
                <div style="text-align: center; padding: 10px; background: #f5f5f5;">
                    <a href="{{ route('admin.notificaciones.index') }}">Ver todas</a>
                </div>
            </div>
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
            <div id="user-menu">
                <ul style="list-style: none; margin: 0; padding: 0;">
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

<!-- JS de notificaciones -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('notificaciones-toggle');
        const dropdown = document.getElementById('notificaciones-dropdown');

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && !toggle.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });
</script>