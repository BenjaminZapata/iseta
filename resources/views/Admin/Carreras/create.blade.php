@extends('Admin.template')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Admin/create-carrera.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CARRERA'])
            <div class="perfil__info">

                <form action="{{ route('admin.carreras.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="p-2" style="margin: 10px;">
                        <legend class="font-600 font-7">Carrera</legend>
                        <div class="grid-2 gap-2 p-0">
                            <div class="label-input-y-75">
                                <label for="nombre">Nombre:</label>
                                <input type="text" id="nombre" name="nombre"
                                    class="@error('nombre') input-error border-red-500 @enderror"
                                    value="{{ old('nombre') }}">
                                <div class="campo-alert">
                                    @error('nombre')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                            <div class="label-input-y-75">
                                <label for="resolucion">Resolución:</label>
                                <input type="text" id="resolucion" name="resolucion"
                                    class="@error('resolucion') input-error border-red-500 @enderror"
                                    value="{{ old('resolucion') }}">
                                <div class="campo-alert">
                                    @error('resolucion')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                            <div class="label-input-y-75">
                                <label for="anio_apertura">Año de apertura:</label>
                                <input type="text" id="anio_apertura" name="anio_apertura"
                                    class="@error('anio_apertura') input-error border-red-500 @enderror"
                                    value="{{ old('anio_apertura') }}">
                                <div class="campo-alert">
                                    @error('anio_apertura')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                            <div class="label-input-y-75">
                                <label for="anio_fin">Año de cierre:</label>
                                <input type="text" id="anio_fin" name="anio_fin"
                                    class="@error('anio_fin') input-error border-red-500 @enderror"
                                    value="{{ old('anio_fin') }}">
                                <div class="campo-alert">
                                    @error('anio_fin')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                            <div class="label-input-y-75">
                                <label for="observaciones">Observaciones:</label>
                                <textarea id="observaciones" name="observaciones">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </fieldset>
                    <div class="grid-2 gap-2 p-0 align-file-row">
                        <!-- Botón de carga de archivo -->
                        <div class="label-input-y-75 custom-file-upload-wrapper">
                            <label for="resolucion_archivo" class="custom-file-upload-button">
                                <i class="ti ti-file-upload" style="font-size: 1.3em"></i>
                                Seleccionar archivo PDF
                            </label>
                            <input type="file" id="resolucion_archivo" name="resolucion_archivo"
                                class="custom-file-input" accept="application/pdf">

                            @error('resolucion_archivo')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nombre del archivo -->
                        <div class="label-input-y-75">
                            <label class="file-name-label">Archivo seleccionado:</label>
                            <div class="file-name-display-box">
                                <p id="file-name-display">Ningún archivo seleccionado</p>
                            </div>
                        </div>
                    </div>


            </div>
            <div class="botones-derecha">
                <x-btn-cancelar />
                <button type="submit" class="btn_blue"><i class="ti ti-circle-plus"
                        style="font-size: 1.3em; margin-right: 8px;"></i>Guardar carrera</button>
            </div>
        </div>
    </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('resolucion_archivo');
        const output = document.getElementById('file-name-display');

        input.addEventListener('change', function() {
            const file = this.files[0];
            output.textContent = file ? file.name : 'Ningún archivo seleccionado';
        });
    });
</script>
