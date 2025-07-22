@extends('Admin.template')

@section('content')
  <div class="perfil_one br">
    <div class="perfil__header">
    <h2>Notificaciones por Documentación Pendiente</h2>
    </div>

    @if ($notificaciones->count())
    <form action="{{ route('admin.notificaciones.marcarTodas') }}" method="POST" style="margin-bottom: 15px;">
    @csrf
    <button class="btn btn-success">Marcar todas como leídas</button>
    </form>

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
      <tr style="{{ $notificacion->leido ? 'opacity: 0.6;' : '' }}">
      <td>
      <a href="{{ route('admin.notificaciones.leer', $notificacion->id) }}">
      {{ $notificacion->alumno->apellido }}, {{ $notificacion->alumno->nombre }}
      </a>
      </td>
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