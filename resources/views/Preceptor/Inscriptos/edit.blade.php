@extends('preceptor.template')

@section('content')
<div>
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR INSCRIPTO'])
        <div class="perfil__info">
            <form method="post" action="{{ route('preceptor.inscriptos.update', ['inscripto' => $registro->id]) }}">
                @csrf
                @method('put')

                <div class="perfil_dataname">
                    <label>Alumno:
                        <span class="campo_info2">{{ $registro->alumno->apellidoNombre() }}</span>
                    </label>
                </div>
                <div class="perfil_dataname">
                    <label>Carrera:</label>
                    <span class="campo_info2">{{ $registro->carrera->nombre }}</span>
                </div>
                <div class="perfil_dataname">
                    <label>Año inscripcion:</label>
                    <input class="campo_info rounded" value="{{ $registro->anio_inscripcion }}" name="anio_inscripcion">
                </div>
                <div class="perfil_dataname">
                    <label>Indice libro matriz:</label>
                    <input class="campo_info rounded" value="{{ $registro->indice_libro_matriz }}"
                        name="indice_libro_matriz">
                </div>
                <div class="perfil_dataname">
                    <label>Año finalizacion:</label>
                    <input class="campo_info rounded" value="{{ $registro->anio_finalizacion }}"
                        name="anio_finalizacion">
                </div>
                <div class="perfil_dataname">
                    <label>Estado:</label>
                    <select class="campo_info rounded" name="estado" id="estado">
                        <option value="0" {{ $registro->estado == 0 ? 'selected' : '' }}>Cursando</option>
                        <option value="1" {{ $registro->estado == 1 ? 'selected' : '' }}>Egresado/a</option>
                        <option value="2" {{ $registro->estado == 2 ? 'selected' : '' }}>Desertor/ar</option>
                    </select>

                </div>

                <div class="botones-derecha"
                    style="margin-right: 27px; padding-top: 10px; padding-bottom: 16px; display: flex; gap: 12px; justify-content: flex-end;">
                    <x-btn-cancelar />
                    <button class="btn_blue"><i class="ti ti-refresh"
                            style="font-size: 1.3em; margin-right: 8px;"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Boton eliminar 
        @if (!$config['modo_seguro'])
            <div class="upd">
                <form class="form-eliminar" method="POST"
                    action="{{ route('preceptor.inscriptos.destroy', ['inscripto' => $registro->id]) }}">
    @csrf
    @method('delete')
    <button class="btn_red"><i class="ti ti-trash"></i>Eliminar inscripción</button>
    </form>
</div>
@endif
--}}
</div>
@endsection