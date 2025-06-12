<header>
    <nav class="nav items-center">
        <i class="uil uil-bars navOpenBtn"></i>
        <a href="/alumno/inscripciones" class="logo">ISETA</a>
        <ul class="nav-links left-0 left-hide md-left-0">

          <i class="uil uil-times navCloseBtn"></i>


          <li><a @class(['selected'=>request()->is('alumno/cursadas')]) href="{{route('alumno.cursadas')}}">
            <i class="uil uil-books"></i>
            Cursadas</a></li>
          <li><a @class(['selected'=>request()->is('alumno/examenes')]) href="{{route('alumno.examenes')}}">
            <i class="uil uil-folder"></i>
            Examenes</a></li>
          <li><a @class(['selected'=>request()->is('alumno/inscripciones')]) href="{{route('alumno.inscripciones')}}">
            <i class="uil uil-file-edit-alt"></i>
            Inscribir</a></li>
            <li>
              <a @class([
                'selected' => request()->is('alumno/rematriculacion')
              ]) href="{{route('alumno.rematriculacion.asignaturas')}}">
              <i class="ti ti-clipboard-text"></i>Rematriculacion</a>
            </li>
            {{-- @env('local')
              <li><a @class(['selected'=>request()->is('alumno/ayuda')]) href="{{route('alumno.ayuda')}}">
                <i class="uil uil-books"></i>
                Ayuda</a></li>
            @endenv --}}

        </ul>
        @auth('web')

        <div class="perfil-logout" >
          <div class="perfil-logout-btn">
          {{-- <div class="white pointer" onclick="toggle()">--}}
            <!--<div class="perfil-imagen">

            </div> -->
            <span>
              {{auth()->user()->iniciales()}}
              {{-- {{auth()->user()->primerNombre()}} --}}
              <i class="ti ti-chevron-down"></i>
            </span>

          </div>

          <ul class="perfil-lista shadow-2xl">

            <li class="perfil-lista-item">
              <a @class(['bold'=>request()->is('alumno/info')]) href="{{route('alumno.info')}}">
                <i class="uil uil-user"></i> Perfil
              </a>
              </li>

            <li class="perfil-lista-item"><a href="/alumno/logout"><i class="ti ti-logout"></i>Cerrar sesion</a></li>

          </ul>

        </div>
        @endauth
    </nav>

</header>

<script src="{{asset('js/nav-menu.js')}}"></script>
