@extends('Alumnos.layout')
@section('content')
<main id="fondo-estudiantes">
          <section class="table">
            <div class="table__header">
          <h1>Mis cursadas </h1>
          {{-- <div class="filtrar"> --}}
              <form class="none md-block" action="{{route('alumno.cursadas')}}">
              <div class="tabla_botonera">

                <div class="contenedor_ordenar">
                  <span>Ordenar por</span>
                  <select class="ordenar" name="orden">
                      <option @selected($filtros['orden'] == 'anio') value="anio">Año carrera</option>
                      <option @selected($filtros['orden'] == 'anio_cursada') value="anio_cursada">Año cursada</option>
                      <option @selected($filtros['orden'] == 'anio_desc') value="anio_desc">Año carrera desc</option>
                      <option @selected($filtros['orden'] == 'anio_cursada_desc') value="anio_cursada_desc">Año cursada desc</option>
                  </select>
                  <i class="ti ti-arrows-down-up i_ordenar"></i>
                </div>

                <div class="contenedor_filtrar">
                  <span>Filtrar por</span>
                  <select class="filtrar" name="campo">
                      <option value="ninguno">Ninguno</option>
                      <option @selected($filtros['campo'] == 'aprobadas') value="aprobadas">Aprobadas</option>
                      <option @selected($filtros['campo'] == 'desaprobadas') value="desaprobadas">Desaprobadas</option>
                      <option @selected($filtros['campo'] == 'final_aprobado') value="final_aprobado">Final aprobado</option>
                      <option @selected($filtros['campo'] == 'final_desaprobado') value="final_desaprobado">Final desaprobado</option>
                    </select>
                    <i class="ti ti-adjustments i_filtrar"></i>
                </div>
                <div class="contenedor_filtrado">
                      <input class="filtrado-busqueda" value="{{$filtros['filtro']}}" name="filtro" type="text" placeholder="Algebra, Ingles, ...">
                </div>

                <div class="contenedor_btn-busqueda">
                  <input class="btn-buscador" type="submit" value="Buscar">
                </div>

                </div>
            </form>


        </div>
        <div class="table__body">
          <table>
            <thead>
                <th>Materia</th>
                <th>Cursada</th>
                <th class="text-center">Condicion</th>
                {{-- <th class="text-center">Año cursada</th> --}}
                <th>Examen final</th>
                @if ($puedeBajarse)
                  <th>Acción</th>
                @endif
            </thead>
            <tbody>

              @php
                $actual = "";
                $primero = true;
                $anio_actual = "";
              @endphp

              @foreach($cursadas as $key=>$cursada)

                @if($filtros['campo'] == 'final_aprobado' && !in_array($cursada->id_asignatura,$examenesAprobados))
                  @continue
                @elseif($filtros['campo'] == 'final_desaprobado' && in_array($cursada->id_asignatura,$examenesAprobados))
                  @continue
                @endif

                @if ($anio_actual != $cursada->anio)
                  <tr>
                      <td class="p-1 center font-600 tit-year" colspan=6>
                        Año: {{$cursada->asignatura->anio}}
                      </td>
                  </tr>
                  @php
                    $anio_actual = $cursada->anio
                  @endphp
                @endif

                @php
                  if ($actual == $cursada->id_asignatura) {
                    echo '<tr id="td-'.$cursada->id_asignatura.'" class="none">';
                  }else{
                    echo '<tr>';
                  }
                @endphp
              {{-- <tr> --}}

                @if ($actual != $cursada->id_asignatura && !$loop->last && $cursadas[$key+1]->id_asignatura == $cursada->id_asignatura)
                  <td data-label="Materia">
                    <p>{{$cursada->nombre}}</p>
                    <p>Año de cursada: {{$cursada->anio_cursada}}</p>
                    <button class="pointer bg-transparent px-2 mx-5 rounded desplegable" data-element="{{$cursada->id_asignatura}}">↓</button>
                  </td>
                @else
                  <td data-label="Materia">
                    <p>{{$cursada->nombre}}</p>
                    <p>Año de cursada: {{$cursada->anio_cursada}}</p>
                  </td>
                @endif
                @php
                  $actual = $cursada->id_asignatura;
                @endphp



                <td data-label="Cursada">

                    <p @class([
                        'status' => true,
                        'aprobada' => $cursada->aprobada == 1 || $cursada->condicion == 0 || $cursada->condicion == 2 || $cursada->condicion == 3,
                        'reprobada' => $cursada->aprobada == 2,
                        'cursando' => $cursada->aprobada == 3
                    ]) >
                    {{$cursada->aprobado()}}
                    </p>
                </td>
                  <td data-label="Condicion" class="text-center mb-left">{{$cursada->condicionString()}}</td>

                <p class="center">
                  @php
                      $examen = $cursada->asignatura->aproboExamen(auth()->user());
                  @endphp
                  @if ($examen)
                      <td data-label="Examen final">Si ({{$examen->nota}})</td>
                  @else
                    <td data-label="Examen final">Aun sin aprobar</td>
                  @endif
                </p>

                @if ($puedeBajarse)
                  <td data-label="Acción">
                    @if ($cursada->aprobada == 3 || $cursada->condicion==0)
                    <form method="POST" class="form-eliminar" action="{{route('alumno.rematriculacion.delete', ['cursada'=>$cursada->id])}}">
                      @csrf
                      @method('delete')
                      <button class="rounded px-2 py-1 bg-red-400 bajarse2">Bajarse</button>
                    </form>
                    @endif
                  </td>
                @endif

              </tr>
            @endforeach

            </tbody>
          </table>
        </div>

      </section>
      </main>
      <script>
        // const button = document.querySelector('#ver-equiv')
        window.onclick = function(e){

            if(!e.target.classList.contains('desplegable')) return

            let id = e.target.dataset.element
            console.log(document.querySelector('#td-'+id));
            let list = document.querySelector('#td-'+id)

            list.classList.toggle('none')
        }
      </script>
@endsection
