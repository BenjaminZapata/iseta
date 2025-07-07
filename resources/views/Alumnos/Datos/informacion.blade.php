@extends('Alumnos.layout')
@section('content')

<main id="fondo-estudiantes">
  <section class="perfil">
    <div class="perfil_header just-end gap-2">
      <h1>Perfil</h1>
        <div class="w-100p contenedor_select_carrera">

        <form class="flex just-end gap-2" method="POST" action="{{route('alumno.set.default')}}">
          @csrf
          {{-- <select class="w-50p lg-w-auto select-carrera" name="carrera"> --}}
          <select class="w-50p lg-w-auto border-none p-2 bg-white rounded shadow-sm select-carrera" name="carrera">

            @foreach ($alumno->carreras()->get() as $inscripcion)
                <option @selected($inscripcion->carrera->id==$default->id) value="{{$inscripcion->carrera->id}}">
                  {{$inscripcion->carrera->nombre}}
                </option>
            @endforeach
          </select>
          <button class="border-none p-2 btn_edit">Cambiar</button>
        </form>

      </div>
    </div>
    <div class="perfil_body">
      <div class="perfil_one shadow">
        <div class="perfil_subtop"><h2>Información alumno</h2></div>
        <span class="perfil_dataname">Nombres: <b class="px-1 perfil_datainfo">{{$alumno->nombre}}</b></span>
        <span class="perfil_dataname">Apellidos: <b class="px-1 perfil_datainfo">{{$alumno->apellido}}</b></span>
        <span class="perfil_dataname">Mail: <b class="px-1 perfil_datainfo">{{$alumno->email}}</b></b></span>
        <span class="perfil_dataname">DNI: <b class="px-1 perfil_datainfo">{{$alumno->dniPuntos()}}</b></span>
        <span class="perfil_dataname">Telefono: <b class="px-1 perfil_datainfo">{{$alumno->telefono1}}</b></span>
        <span class="perfil_dataname">Telefono 2: <b class="px-1 perfil_datainfo">{{$alumno->telefono2? $alumno->telefono2:'No tienes un numero alternativo'}}</b></span>
        <span class="perfil_dataname">Telefono 3: <b class="px-1 perfil_datainfo">{{$alumno->telefono3? $alumno->telefono3:'No tienes un segundo numero alternativo'}}</b></span>
        <span class="perfil_dataname">Dirección: <b class="px-1 perfil_datainfo">{{$alumno->calle?$alumno->calle:'Sin datos de la direccion'}}</b></span>
        @if ($alumno->casa_numero)
          <span class="perfil_dataname">Numero: <b class="px-1 perfil_datainfo">{{$alumno->casa_numero}}</b></span>
        @endif
      </div>


        <div class="perfil_second shadow">
            <form action="{{route('cambio.password')}}" method="POST">
          @csrf


        <div class="perfil_subtop"><h2>Editar contraseña</h2></div>
        <div class="w-100p sm-w-75p md-w-50p  perfil_password">
          <input class="w-100p" type="password" name="oldPassword"  required placeholder="Contraseña actual">
          {{-- <i class="ti ti-circle-check-filled check_pos"></i>
          <i class="ti ti-circle-x-filled check_neg"></i> --}}
        </div>
        <div class="w-100p sm-w-75p md-w-50p  perfil_password">
          <input class="w-100p" type="password" name="newPassword"  required placeholder="Nueva contraseña">
          {{-- <i class="ti ti-circle-check-filled check_pos"></i>
          <i class="ti ti-circle-x-filled check_neg"></i> --}}
        </div>
        <div class="w-100p sm-w-75p md-w-50p  perfil_password">
          <input class="w-100p" type="password" name="newPassword_confirmation"  required placeholder="Confirmar contraseña">
          {{-- <i class="ti ti-circle-check-filled check_pos"></i>
          <i class="ti ti-circle-x-filled check_neg"></i> --}}
        </div>
        <div class="w-100p flex just-end perfil_password_refresh">
          <button class="w-17 white border-none p-3 rounded-1 bg-indigo-900"><i class="ti ti-refresh"></i>  Actualizar</button>
        </div>
    </form>
     </div>


      <div class="perfil_third shadow">
        <div class="perfil_subtop"><h2>Academico</h2></div>
        <div class=" perfil_descargable"> <!---flex just-between----->
          <span>Informe analitico</span> <!-- class="font-3"--->
          <a href="/alumno/analitico" target="_blank"><button><i class="ti ti-eye"></i>Observar</button></a> <!--class="white rounded-1 border-none p-3  bg-indigo-900-->

        </div>
        <div class="perfil_descargable"> <!--flex just-between items-center-->
          <span class="md-none">Const. inscripción</span>
          <span class="none md-block">Constancia de inscripción a mesas de examen</span> <!-- class="none md-block font-3"--->
          <a href="{{route('alumno.constancia')}}" target="_blank"><button><i class="ti ti-eye"></i>Observar</button></a> <!--- class="white rounded-1 border-none p-3  bg-indigo-900"--->
        </div>

      </div>
    </div>
  </section>

</main>
@endsection
