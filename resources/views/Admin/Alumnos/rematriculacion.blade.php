@extends('Admin.template')
@section('content')
<link rel="stylesheet" href="{{asset('css/Admin/rematriculacion.css')}}">

@include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ALUMNOS'])

<div id="fondo-estudiantes" class="bg-light flex-col justify-center items-center gap-4 p-4 w-100">
    <div class="info-box bg-white p-3 rounded shadow-sm text-sm">
        <p>Si solo desea registrar que un alumno está inscripto en una carrera sin anotarlo en ninguna cursada, deje todos los campos con el valor "No matricular" y haga click en enviar.</p>
        <p>Al hacer esto el alumno podrá visualizar esta carrera en el seleccionador de carreras y podrá inscribirse a las cursadas manualmente.</p>
    </div>

    <div class="perfil_one br w-100p max-w-900">
        <div class="perfil__header mb-3">
            <h2 class="text-lg font-bold border-b pb-2">Matricular</h2>
        </div>

        <div class="perfil__info">
            <form method="POST" action="{{route('admin.alumno.matricular.post', ['alumno' => $alumno->id, 'carrera' => $carrera->id])}}">
                @csrf

                @if (count($asignaturas) <= 0)
                    <div class="alert-box bg-warning p-3 rounded text-center">
                    <p>No tienes asignaturas para rendir de esta carrera.</p>
                    <p>Si crees que se trata de un error, comunicate con la institución para solucionarlo.</p>
        </div>
        @else
        @foreach ($asignaturas as $asignatura)
        <div class="asignatura-card bg-white p-3 rounded shadow-sm mb-3 @if($asignatura->equivalencias_previas) border-left-warning @endif">
            <div class="flex justify-between items-center mb-2">
                <div>
                    <label class="font-semibold">Año:</label>
                    <span>{{$asignatura->anio}}</span>
                </div>
                <div>
                    <label class="font-semibold">Asignatura:</label>
                    <a href="{{route('admin.asignaturas.edit', ['asignatura' => $asignatura->id])}}" class="text-blue-600 hover:underline">{{$asignatura->nombre}}</a>
                </div>
            </div>

            <div>
                @if ($asignatura->equivalencias_previas)
                <div class="flex justify-between items-center mb-2">
                    <p class="font-semibold text-warning">Debes correlativas</p>
                    <button type="button" class="btn-sm btn-outline-primary ver-equiv" data-element="{{$asignatura->id}}">Detalles...</button>
                </div>
                <ul class="equiv-list none id-{{$asignatura->id}} pl-4 list-disc text-sm">
                    @foreach ($asignatura->equivalencias_previas as $equiv)
                    <li><strong>{{$equiv->anioStr()}}:</strong> {{$equiv->nombre}}</li>
                    @endforeach
                </ul>
                @else
                <select class="form-select w-full mt-2" name="{{$asignatura->id}}">
                    <option value="">No matricular</option>
                    <option @selected(old($asignatura->id) == 2) value="2">Regular</option>
                    <option @selected(old($asignatura->id) == 1) value="1">Libre</option>
                    <option @selected(old($asignatura->id) == 3) value="3">Promoción</option>
                    <option @selected(old($asignatura->id) == 4) value="4">Equivalencia</option>
                </select>
                @endif
            </div>
        </div>
        @endforeach

        <div class="text-right mt-4">
            <button class="btn btn-primary"><i class="ti ti-send"></i> Enviar</button>
        </div>
        @endif
        </form>
    </div>
</div>
</div>

<script>
    window.onclick = function(e) {
        if (!e.target.classList.contains('ver-equiv')) return;
        let id = e.target.dataset.element;
        let list = document.querySelector('.id-' + id);
        list.classList.toggle('none');
    }
</script>
@endsection