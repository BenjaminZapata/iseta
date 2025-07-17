<aside class="group fixed top-0 left-0 h-screen bg-white shadow-md transition-all duration-500 z-50 overflow-hidden"
    style="width: 4rem;"
    onmouseover="this.style.width='16rem'"
    onmouseout="this.style.width='4rem'">
    <div class="relative h-20 flex items-center justify-center bg-[#140b5c]">
        {{-- Logo colapsado (chico) --}}
        <img src="{{ asset('img/logo-mini.png') }}" alt="Logo Mini"
            class="w-10 absolute transition-opacity duration-300 group-hover:opacity-0" style="margin-left: 0%">
        
        {{-- Logo expandido (grande) --}}
        <img src="{{ asset('img/logo.png') }}" alt="Logo Completo"
            class="w-48 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </div>

    <ul class="flex flex-col gap-2">
        <li class="text-blue-600">
            <a href="{{route('admin.alumnos.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-user text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Alumnos</span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.profesores.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-users text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Profesores</span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.carreras.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-address-book text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Carreras</span>
            </a>
        </li>
        <!-- <li><a class="text-blue-600" href="" style="display: block;"> Asignaturas </a></li> -->
        <li>
            <a href="{{route('admin.mesas.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-address-book text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Mesas</span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.cursadas.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-books"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Cursadas</span>
            </a>
        </li>
        <!--  <li><a class="text-blue-600" href="{" style="display: block;"><i class="ti ti-address-book"></i> Examenes </a></li> -->
         <li>
            <a href="{{route('admin.inscriptos.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-file-invoice"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Inscriptos</span>
            </a>
        </li> 
        <!-- Repetir el patrón para el resto -->
        <hr class="my-2 border-gray-200">
        <li>
            <a href="{{route('admin.admins.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-user-cog text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Admins</span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.config.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-settings text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Configuración</span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.habiles.index')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-calendar-time text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Días no hábiles</span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.config.modoseguro')}}" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-shield-lock text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">
                    {{ $config['modo_seguro'] ? 'Desactivar modo seguro' : 'Activar modo seguro' }}
                </span>
            </a>
        </li>
        <li>
            <a href="/admin/logout" class="flex items-center gap-2 px-4 py-2 text-blue-600 hover:bg-gray-100 transition-all">
                <i class="ti ti-logout text-xl"></i>
                <span class="transition-all duration-300 opacity-0 group-hover:opacity-100 whitespace-nowrap">Cerrar sesión</span>
            </a>
        </li>
    </ul>
</aside>
