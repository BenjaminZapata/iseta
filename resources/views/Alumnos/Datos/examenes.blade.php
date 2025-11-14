@extends('Alumnos.layout')

@section('content')
<main id="fondo-estudiantes">
    <section class="table">
<div class="table__header">
          <h1>Examenes</h1>
          
          <form class="none md-block" action="{{route('alumno.examenes')}}">
            <div class="tabla_botonera">
              {{-- <div >
                <p class="promedio">Promedio: {{$promedio}}</p>
              </div> --}}
              
              <div class="contenedor_ordenar">
                <span>Ordenar por</span>
                <select class="ordenar" name="orden">
                  <option @selected($filtros['orden'] == 'asignatura') value="asignatura">Asignatura</option>
                    <option @selected($filtros['orden'] == 'anio') value="anio">Año carrera</option>
                    <option @selected($filtros['orden'] == 'fecha') value="fecha">Año cursada</option>
                    <option @selected($filtros['orden'] == 'anio_desc') value="anio_desc">Año carrera desc</option>
                    <option @selected($filtros['orden'] == 'fecha_desc') value="fecha_desc">Año cursada desc</option>
                </select>
                <i class="ti ti-arrows-down-up i_ordenar"></i>
              </div>
              <div class="none md-block contenedor_filtrar">
                <span>Filtrar por</span>
                <select  name="campo" class="filtrar">
                  <option  value="todas">Todas</option>

                  <option @selected($filtros['campo'] == 'aprobadas') value="aprobadas">Aprobadas</option>
                  <option @selected($filtros['campo'] == 'desaprobadas') value="desaprobadas">Desaprobadas</option>
                </select>
                <i class="ti ti-adjustments i_filtrar"></i>
              </div>
                
              <div class="contenedor_filtrado">
                <input name="filtro" class="filtrado-busqueda" value="{{$filtros['filtro']}}" placeholder="Algebra, Ingles, ...">
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
          <th>Profesores</th>
          <th>Nota</th>
          <th>Tipo de final</th>
          <th>Fecha</th>
        
      </thead>
      <tbody>
        @php
            $actual = "";
            $primero = true;
            $anio_actual = "";
        @endphp
        @foreach($examenes as $key => $examen)
          
          
          @php  
            if ($actual == $examen->id_asignatura) {
              echo '<tr id="td-'.$examen->id_asignatura.'" class="none">';  
            }else{
              echo '<tr>';
            }
          @endphp

        @if ($anio_actual != $examen->anio)
                  <tr>
                      <td class="p-1 center font-600 tit-year" colspan="5">
                        Año: {{$examen->asignatura->anio}}
                      </td>
                  </tr>
                  @php
                    $anio_actual = $examen->anio
                  @endphp
                @endif

        
          
          
          @if ($actual != $examen->id_asignatura && !$loop->last && $examenes[$key+1]->id_asignatura == $examen->id_asignatura)
            <td data-label="Materia" class="flex items-center">
              {{$examen->nombre}}
              <button class="btn-mov pointer bg-transparent px-2 mx-5 desplegable" data-element="{{$examen->id_asignatura}}">⇅</button> 
            </td>
          @else
            <td data-label="Materia">{{$examen->nombre}}</td>    
          @endif
          @php
            $actual = $examen->id_asignatura;
        @endphp  
          <td data-label="Profesores">
              @if(isset($examen->mesa))
            <p><i class="ti ti-user-filled"></i> {{$examen->mesa->profesorNombre('presidente')}}</p>
            <p><i class="ti ti-user-filled"></i> {{$examen->mesa->profesorNombre('vocal1','A confirmar')}}</p>
            
            @if ($examen->mesa->prof_vocal_2)
              <p><i class="ti ti-user-filled"></i> {{$examen->mesa->profesorNombre('vocal2','Desconocido')}}</p>
            @endif

            
              @else
               <p>No hay datos de mesa</p>
              @endif
          </td>
            <td data-label="Nota">
              {{$examen->notaTexto()}}
        
            </td>
            <td data-label="Tipo de final">{{$examen->tipoFinalTexto()}}</td>
            <td data-label="Fecha">
             {{$formatoFecha->dma($examen->getFecha())}}
            </td>
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
