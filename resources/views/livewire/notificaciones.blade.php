<div class="relative">
    <!-- Botón de campana + flecha -->
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
            <div class="px-4 py-2 border-b border-gray-300 text-right">
                <button wire:click="borrarTodas" class="text-sm text-red-600 hover:underline font-bold">
                    Borrar todas
                </button>
            </div>
        @endif

        {{-- Lista de notificaciones --}}
        @forelse ($notificaciones as $notificacion)
            <div class="px-4 py-3 border-b border-gray-200 hover:bg-gray-50"
                style="{{ is_null($notificacion->read_at) ? 'font-weight: bold;' : '' }}">

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="#" wire:click.prevent="marcarComoLeida('{{ $notificacion->id }}')"
                        class="block text-gray-800">
                        <h3 class="text-sm mb-1">{{ $notificacion->data['title'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $notificacion->data['message'] }}</p>
                    </a>

                    <button wire:click="borrarNotificacion('{{ $notificacion->id }}')"
                        class="text-red-500 hover:text-red-700" style="margin-left: 10px; font-size: 1rem;">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="px-4 py-4 text-center text-gray-400 bold" style="color: black;">
                No hay notificaciones
            </div>
        @endforelse
    </div>
</div>
