<aside id="sidebar" onmouseover="this.style.width='16rem'" onmouseout="this.style.width='4rem'">
    <div class="sidebar-header">
        {{-- Logo colapsado (chico) --}}
        <img src="{{ asset('img/logo-mini.png') }}" alt="Logo Mini" class="logo-mini">

        {{-- Logo expandido (grande) --}}
        <img src="{{ asset('img/logo.png') }}" alt="Logo Completo" class="logo-full">
    </div>

    <ul>
        <li>
            <a href="{{ route('preceptor.alumnos.index') }}">
                <i class="ti ti-user"></i>
                <span>Alumnos</span>
            </a>
        </li>
        <li>
            <a href="{{ route('preceptor.cursadas.index') }}">
                <i class="ti ti-books"></i>
                <span>Cursadas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('preceptor.mesas.index') }}">
                <i class="ti ti-address-book"></i>
                <span>Mesas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('preceptor.carreras.index') }}">
                <i class="ti ti-school"></i>
                <span>Carreras</span>
            </a>
        </li>
        <li>
            <a href="{{ route('preceptor.asignaturas.index') }}">
                <i class="ti ti-book"></i>
                <span>Asignaturas</span>
            </a>
        </li>
        
    </ul>
</aside>