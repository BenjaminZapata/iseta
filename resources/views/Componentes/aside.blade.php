<aside class="none lg-block admin-aside">
    <h1>
        <div>
            <img src="{{ asset('img/logo.png') }}" alt="" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
        </div>
    </h1>
    <ul>
        <li><a class="text-blue-600" href="{{route('admin.alumnos.index')}} " style="display: block;"><i class="ti ti-user"></i>Alumnos</a></li>
        <li><a class="text-blue-600" href="{{route('admin.profesores.index')}}" style="display: block;"><i class="ti ti-users"></i> Profesores </a></li>
        <li><a class="text-blue-600" href="{{route('admin.carreras.index')}}" style="display: block;"><i class="ti ti-address-book"></i> Carreras </a></li>
        {{-- <li><a class="text-blue-600" href="{{route('admin.asignaturas.index')}}" style="display: block;"> Asignaturas </a></li> --}}
        <li><a class="text-blue-600" href="{{route('admin.mesas.index')}}" style="display: block;"><i class="ti ti-clipboard-text"></i> Mesas </a></li>
        {{-- <li><a class="text-blue-600" href="{{route('admin.examenes.index')}}" style="display: block;"><i class="ti ti-address-book"></i> Examenes </a></li> --}}
        <li><a class="text-blue-600" href="{{route('admin.cursadas.index')}}" style="display: block;"><i class="ti ti-books"></i></i> Cursadas </a></li>
        <li><a class="text-blue-600" href="{{route('admin.inscriptos.index')}}" style="display: block;"><i class="ti ti-file-invoice"></i> Inscriptos </a></li>
        <hr>
        <li><a class="text-blue-600" href="{{route('admin.admins.index')}}  " style="display: block;"><i class="ti ti-user-cog"></i> Admins </a></li>
        <li><a class="text-blue-600" href="{{route('admin.config.index')}}  " style="display: block;"><i class="ti ti-settings"></i> Configuracion </a></li>
        <li><a class="text-blue-600" href="{{route('admin.habiles.index')}}  " style="display: block;"><i class="ti ti-calendar-time"></i> Dias no habiles </a></li>

        <div class="aside-end">
            <li>
                <a class="text-blue-600" href="{{route('admin.config.modoseguro')}}" style="display: block;"><i class="ti ti-shield-lock"></i>
                    @if ($config['modo_seguro'])
                    Desactivar modo seguro
                    @else
                    Activar modo seguro
                    @endif
                </a>
            </li>
            <li><a href="/admin/logout" style="display: block;"><i class="ti ti-logout"></i> Cerrar sesion</a></li>
        </div>
    </ul>


</aside>