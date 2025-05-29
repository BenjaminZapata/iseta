<aside class="none lg-block admin-aside">
    <h1 class="logo-iseta">ISETA Admin</h1>
    <ul>
        <li><a class="text-blue-600" href="{{route('admin.alumnos.index')}} "><i class="ti ti-user"></i>Alumnos</a></li>
        <li><a class="text-blue-600" href="{{route('admin.profesores.index')}}"><i class="ti ti-users"></i>    Profesores  </a></li>
        <li><a class="text-blue-600" href="{{route('admin.carreras.index')}}"><i class="ti ti-address-book"></i>      Carreras    </a></li>
        {{-- <li><a class="text-blue-600" href="{{route('admin.asignaturas.index')}}">   Asignaturas </a></li> --}}
        <li><a class="text-blue-600" href="{{route('admin.mesas.index')}}"><i class="ti ti-clipboard-text"></i>         Mesas       </a></li>
        {{-- <li><a class="text-blue-600" href="{{route('admin.examenes.index')}}"><i class="ti ti-address-book"></i>      Examenes    </a></li> --}}
        <li><a class="text-blue-600" href="{{route('admin.cursadas.index')}}"><i class="ti ti-books"></i></i>      Cursadas    </a></li>
        <li><a class="text-blue-600" href="{{route('admin.inscriptos.index')}}"><i class="ti ti-file-invoice"></i>      Inscriptos    </a></li>
        <hr>
        <li><a class="text-blue-600" href="{{route('admin.admins.index')}}  "><i class="ti ti-user-cog"></i>      Admins      </a></li>
        <li><a class="text-blue-600" href="{{route('admin.config.index')}}  "><i class="ti ti-settings"></i>      Configuracion      </a></li>
        <li><a class="text-blue-600" href="{{route('admin.habiles.index')}}  "><i class="ti ti-calendar-time"></i>      Dias no habiles      </a></li>

        <div class="aside-end">
            <li>
                <a class="text-blue-600" href="{{route('admin.config.modoseguro')}}"><i class="ti ti-shield-lock"></i>
                    @if ($config['modo_seguro'])
                        Desactivar modo seguro
                    @else
                          Activar modo seguro
                    @endif
                </a>
            </li>
            <li><a href="/admin/logout"><i class="ti ti-logout"></i> Cerrar sesion</a></li>
        </div>
    </ul>


</aside>
