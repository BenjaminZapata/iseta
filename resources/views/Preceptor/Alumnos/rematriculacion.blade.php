@extends('preceptor.template')
@section('content')

    <main id="fondo-estudiantes" class="black flex-col justify-center items-center gap-3 p-3 w-100">
        <p class="w-100p">
            <a href="/preceptor/alumnos">Alumnos</a>/
            <a href="/preceptor/alumnos/{{$alumno->id}}/edit">{{$alumno->id}}</a>/ Rematricular/
            <a href="/preceptor/matricular/{{$alumno->id}}?carrera={{$carrera->id}}">{{$carrera->nombre}}</a>
        </p>
        <p>Si solo desea registrar que un alumno esta inscripto en una carrera sin anotarlo en ninguna cursada, deje todos
            los campos con el valor "No matricular" y haga click en enviar!</p>
        <p>Al hacer esto el alumno podra visualizar esta carrera en el seleccionador de carreras y podra inscribirse a las
            cursadas manualmente.</p>

        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Matricular</h2>
            </div>
            <div class="perfil__info">

                <form method="POST"
                    action="{{route('preceptor.alumno.matricular.post', ['alumno' => $alumno->id, 'carrera' => $carrera->id])}}">
                    @csrf

                    @if (count($asignaturas) <= 0)
                        <div class="w-100p flex-col">
                            <span>No tienes asignaturas para rendir de esta carrera.</span>
                            <span>Si crees que se trata de un error, comunicate con la institucion para solucionarlo.</span>
                        </div>

                    @else
                        @foreach ($asignaturas as $asignatura)
                            <div class="w-100p flex just-between perfil_dataname-rem">

                                <div class="flex remat" @class([
                                    'gray-600' => $asignatura->equivalencias_previas
                                ])>
                                    <div class="flex">
                                        <label>Año:</label>
                                        <span class="font-400">{{$asignatura->anio}}</span>
                                    </div>
                                    <div class="flex">
                                        <label>Asignatura:</label>
                                        <a href="{{route('admin.asignaturas.edit', ['asignatura' => $asignatura->id])}}"><span
                                                class="font-400">{{$asignatura->nombre}}</span></a>
                                    </div>
                                </div>
                                <div class="flex-col">
                                    @if ($asignatura->equivalencias_previas)
                                        <div class="flex just-end gap-3">
                                            <p class="font-600">Debes correlativas</p>
                                            <label class="blue-600 px-1 rounded pointer ver-equiv"
                                                data-element="{{$asignatura->id}}">Detalles...</label>
                                        </div>
                                        <ul class="none id-{{$asignatura->id}}">
                                            @foreach ($asignatura->equivalencias_previas as $asignatura)
                                                <li class="salto"><span class="font-600">{{$asignatura->anioStr()}}:</span>
                                                    {{$asignatura->nombre}}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <select class="campo_info-rem" name="{{$asignatura->id}}">
                                            <option value="">No matricular</option>
                                            <option @selected(old($asignatura->id) == 2) value="2">Regular</option>
                                            <option @selected(old($asignatura->id) == 1) value="1">Libre</option>
                                            <option @selected(old($asignatura->id) == 3) value="3">Promocion</option>
                                            <option @selected(old($asignatura->id) == 4) value="4">Equivalencia</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="upd"><button class="btn_blue"><i class="ti ti-send"></i>Enviar</button></div>
                    @endif
                </form>
            </div>
        </div>

    </main>

    <script>
        const button = document.querySelector('#ver-equiv')
        window.onclick = function (e) {
            if (!e.target.classList.contains('ver-equiv')) return
            let id = e.target.dataset.element
            let list = document.querySelector('.id-' + id)
            console.log(list);
            list.classList.toggle('none')
        }
    </script>
@endsection