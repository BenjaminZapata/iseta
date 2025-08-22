<div class="relative">
    <!-- Botón de campana + flecha -->
    <div id="notificaciones-toggle" style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;"
        onclick="toggleNotificacionesMenu()">
        <i class="ti ti-bell campana"></i>
        <i id="notificaciones-arrow" class="ti ti-chevron-down"
            style="color: white; font-size: 1rem; position: relative; top: -2px; transition: transform 0.2s ease;"></i>
    </div>

    <!-- Dropdown (oculto por defecto como el avatar) -->
    <div id="notificaciones-menu" class="header-dropdown-bell">
        @forelse ($notificaciones as $notificacion)
            <div class="px-4 py-3 border-b border-gray-200 hover:bg-gray-50">
                <a href="#" wire:click.prevent="marcarComoLeida('{{ $notificacion->id }}')"
                    class="block text-gray-800">
                    <h3 class="font-semibold text-sm mb-1">{{ $notificacion->data['title'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $notificacion->data['message'] }}</p>
                </a>
            </div>
        @empty
            <div class="px-4 py-4 text-center text-gray-400 bold" style="color: black;">
                No hay notificaciones
            </div>
        @endforelse
    </div>
</div>
