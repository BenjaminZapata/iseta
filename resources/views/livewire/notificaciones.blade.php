<div class="relative">
    <!-- Botón de campana + flecha -->
    <div id="notificaciones-toggle" class="flex items-center gap-1 cursor-pointer" onclick="toggleNotificacionesMenu()">
        <i class="ti ti-bell text-2xl text-gray-700"></i>
        <i id="notificaciones-arrow"
            class="ti ti-chevron-down text-gray-700 transition-transform duration-200 ease-in-out"></i>
    </div>

    <!-- Dropdown flotante -->
    <div id="notificaciones-menu" class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg z-50 hidden">
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

<!-- Script -->
<script>
    function toggleNotificacionesMenu() {
        const menu = document.getElementById('notificaciones-menu');
        const arrow = document.getElementById('notificaciones-arrow');
        const isOpen = !menu.classList.contains('hidden');

        if (isOpen) {
            menu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        } else {
            menu.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        }
    }

    // Cerrar el menú si haces clic fuera
    document.addEventListener('click', function(event) {
        const toggle = document.getElementById('notificaciones-toggle');
        const menu = document.getElementById('notificaciones-menu');
        const arrow = document.getElementById('notificaciones-arrow');

        if (!toggle.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    });
</script>
