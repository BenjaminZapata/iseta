@extends('Admin.template')

@section('content')
 <div class="perfil_one br">
  <div class="perfil__header">
   <h2>Notificaciones por Documentación Pendiente</h2>
  </div>

  @if ($notificaciones->count())
   <table class="table">
    <thead>
  <tr>
   <th>Alumno</th>
   <th>Mensaje</th>
   <th>Fecha</th>
   <th>Leído</th>
  </tr>
    </thead>
    <tbody>
  @foreach ($notificaciones as $notificacion)
   <tr>
    <td>{{ $notificacion->alumno->apellido }}, {{ $notificacion->alumno->nombre }}</td>
    <td>{{ $notificacion->mensaje }}</td>
    <td>{{ \Carbon\Carbon::parse($notificacion->fecha)->format('d/m/Y') }}</td>
    <td>
  @if ($notificacion->leido)
   <span class="badge bg-success">Sí</span>
  @else
   <span class="badge bg-warning text-dark">No</span>
  @endif
    </td>
   </tr>
  @endforeach
    </tbody>
   </table>

   <div style="margin-top: 20px;">
    {{ $notificaciones->links() }}
   </div>
  @else
   <p>No hay notificaciones registradas.</p>
  @endif
 </div>
@endsection