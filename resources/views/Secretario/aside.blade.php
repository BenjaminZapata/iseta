<aside id="sidebar" onmouseover="this.style.width='16rem'" onmouseout="this.style.width='4rem'">
    <div class="sidebar-header">
        {{-- Logo colapsado (chico) --}}
        <img src="{{ asset('img/logo-mini.png') }}" alt="Logo Mini" class="logo-mini">

        {{-- Logo expandido (grande) --}}
        <img src="{{ asset('img/logo.png') }}" alt="Logo Completo" class="logo-full">
    </div>

    <ul>
       <li>
            <a href="{{ route('secretario.alumnos.index') }}">
                <i class="ti ti-user"></i>
                <span>Alumnos</span>
            </a>
        </li>
      
      <li>
            <a href="{{ route('secretario.profesores.index') }}">
                <i class="ti ti-users"></i>
                <span>Profesores</span>
            </a>
        </li>
       
     
      
        
    </ul>
</aside>