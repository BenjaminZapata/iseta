<head>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
</head>

<aside 
    x-data="{ open: true }" 
    :class="open ? 'w-auto' : 'w-auto'" 
    class="admin-aside bg-white h-screen transition-all duration-300 overflow-hidden fixed"
>
    <!-- Botón para plegar/desplegar -->
    <div class="flex justify-end p-2">
        <button @click="open = !open" class="text-blue-600 focus:outline-none">
            <i :class="open ? 'ti ti-chevron-left' : 'ti ti-chevron-right'"></i>
        </button>
    </div>

    <!-- Logo -->
    <h1 class="logo-iseta px-4 py-2 font-bold text-lg" x-show="open">ISETA Admin</h1>

    <!-- Menú -->
    <ul>
        <li>
            <a class="text-blue-600 flex items-center gap-2 p-2 hover:bg-blue-100" href="{{route('admin.alumnos.index')}}">
                <i class="ti ti-user"></i>
                <span x-show="open">Alumnos</span>
            </a>
        </li>
        <li>
            <a class="text-blue-600 flex items-center gap-2 p-2 hover:bg-blue-100" href="{{route('admin.profesores.index')}}">
                <i class="ti ti-users"></i>
                <span x-show="open">Profesores</span>
            </a>
        </li>
        <li>
            <a class="text-blue-600 flex items-center gap-2 p-2 hover:bg-blue-100" href="{{route('admin.carreras.index')}}">
                <i class="ti ti-users"></i>
                <span x-show="open">Carreras</span>
            </a>
        </li>
        <li>
            <a class="text-blue-600 flex items-center gap-2 p-2 hover:bg-blue-100" href="{{route('admin.mesas.index')}}">
                <i class="ti ti-users"></i>
                <span x-show="open">Mesas</span>
            </a>
        </li>
        <li>
            <a class="text-blue-600 flex items-center gap-2 p-2 hover:bg-blue-100" href="{{route('admin.cursadas.index')}}">
                <i class="ti ti-users"></i>
                <span x-show="open">Cursadas</span>
            </a>
        </li>
    </ul>

    <!-- Parte inferior -->
    <div class="mt-auto">
        <ul>
           <li>
                <a class="text-blue-600 flex items-center gap-2 p-2 hover:bg-blue-100" href="/admin/logout">
                    <i class="ti ti-logout"></i>
                    <span x-show="open">Cerrar sesión</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

