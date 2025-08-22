<div class="relative">
    <!-- Botón de campana + globo de cantidad -->
    <div id="notificaciones-toggle" class="bell" onclick="toggleNotificacionesMenu()">
        <i class="ti ti-bell campana"></i>

        @if ($notificaciones->whereNull('read_at')->count() > 0)
            <span class="counter">
                {{ $notificaciones->whereNull('read_at')->count() }}
            </span>
        @endif

        <i id="notificaciones-arrow" class="ti ti-chevron-down"
            style="color: white; font-size: 1rem; position: relative; top: -2px; transition: transform 0.2s ease;"></i>
    </div>

    <!-- Dropdown de notificaciones -->
    <div id="notificaciones-menu" class="header-dropdown-bell">
        {{-- Botón borrar todas --}}
        @if ($notificaciones->count() > 0)
            <div class="notificaciones-header">
                <button wire:click="borrarTodas" class="borrar-todas-btn">
                    Borrar todas
                </button>
            </div>
        @endif

        {{-- Lista de notificaciones --}}
        @forelse ($notificaciones as $notificacion)
            <div class="notificacion-item {{ is_null($notificacion->read_at) ? 'unread' : '' }}">
                <div class="notificacion-contenido">
                    <a href="#" wire:click.prevent="marcarComoLeida('{{ $notificacion->id }}')">
                        <h3>{{ $notificacion->data['title'] }}</h3>
                        <p>{{ $notificacion->data['message'] }}</p>
                    </a>

                    <button wire:click="borrarNotificacion('{{ $notificacion->id }}')" class="borrar-btn"
                        title="Eliminar">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="notificacion-vacia">
                No hay notificaciones
            </div>
        @endforelse
    </div>
</div>
