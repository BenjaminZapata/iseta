@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Ficha inscripto</h2>
            </div>
            <div class="perfil__info">
                <form method="post" action="{{route('admin.inscriptos.update', ['inscripto' => $registro->id])}}">
                @csrf
                @method('put')

                    <div class="perfil_dataname">
                        <label>Alumno:
                        <span class="campo_info2">{{$registro->alumno->apellidoNombre()}}</span>
                    </label>
                    </div>
                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <span class="campo_info2">{{$registro->carrera->nombre}}</span>
                    </div>
                    <div class="perfil_dataname">
                        <label>Año inscripcion:</label>
                        <input class="campo_info rounded" value="{{$registro->anio_inscripcion}}" name="anio_inscripcion">
                    </div>
                    <div class="perfil_dataname">
                        <label>Indice libro matriz:</label>
                        <input class="campo_info rounded" value="{{$registro->indice_libro_matriz}}" name="indice_libro_matriz">
                    </div>
                    <div class="perfil_dataname">
                        <label>Año finalizacion:</label>
                        <input class="campo_info rounded" value="{{$registro->anio_finalizacion}}" name="anio_finalizacion">
                    </div>
                    <div class="perfil_dataname">
                        <label>Estado:</label>
                        <select class="campo_info rounded" name="estado" id="estado">
        <option value="0" {{ $registro->estado == 0 ? 'selected' : '' }}>Cursando</option>
        <option value="1" {{ $registro->estado == 1 ? 'selected' : '' }}>Egresado/a</option>
        <option value="2" {{ $registro->estado == 2 ? 'selected' : '' }}>Desertor/ar</option>
    </select>

                    </div>

                    <div class="upd"><button class="btn_blue"><i class="ti ti-refresh"></i>Actualizar</button></div>
                </form>
            </div>
        </div>
@if (!$config['modo_seguro'])
        <div class="upd">
            <form class="form-eliminar" method="POST" action="{{route('admin.inscriptos.destroy', ['inscripto' => $registro->id])}}">
                @csrf
                @method('delete')
                <button class="btn_red"><i class="ti ti-trash"></i>Eliminar inscripción</button>
            </form>
        </div>  
        @endif
    </div>
@endsection
