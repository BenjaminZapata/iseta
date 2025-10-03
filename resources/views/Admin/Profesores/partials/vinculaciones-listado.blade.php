<h3 class="mb-3">Vinculaciones actuales</h3>

@if ($vinculaciones->isEmpty())
<p class="text-muted">Este profesor aún no tiene asignaturas vinculadas.</p>
@else
<div class="table-responsive mb-4">
 <table class="table table-bordered table-hover">
  <thead>
   <tr>
    <th>Carrera</th>
    <th>Asignatura</th>
    <th>Año</th>
    <th>Módulo</th>
    <th>Carga horaria</th>
   </tr>
  </thead>
  <tbody>
   @foreach ($vinculaciones as $v)
   <tr>
    <td>{{ $v['carrera'] }}</td>
    <td>{{ $v['asignatura'] }}</td>
    <td>{{ $v['anio'] }}</td>
    <td>{{ $v['tipo_modulo'] }}</td>
    <td>{{ $v['carga_horaria'] }} hs</td>
   </tr>
   @endforeach
  </tbody>
 </table>
</div>
@endif

<a href="{{ route('admin.profesores.vincular', $profesor) }}" class="btn btn-outline-primary">
 Vincular asignaturas
</a>