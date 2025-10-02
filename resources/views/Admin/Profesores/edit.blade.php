@extends('Admin.template')

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR PROFESOR/A'])

        <div class="perfil__info">
            <p class="info-obligatorios">
                Los campos marcados con <span style="color:red">*</span> son obligatorios.
            </p>

            {{-- Formulario para actualizar profesor --}}
            <form action="{{ route('admin.profesores.update', ['profesor' => $profesor->id]) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Profesor --}}
                <fieldset>
                    <legend>Profesor</legend>
                    <div class="form-group">
                        <label for="nombre">Nombre:* </label>
                        <input type="text" name="nombre" id="nombre"
                               class="label-input-y-75 form-control"
                               value="{{ old('nombre', $profesor->nombre) }}"
                               placeholder="Ej: Juan" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido:* </label>
                        <input type="text" name="apellido" id="apellido"
                               class="label-input-y-75 form-control"
                               value="{{ old('apellido', $profesor->apellido) }}"
                               placeholder="Ej: Pérez" maxlength="30">
                    </div>
                    <div class="form-group">
                        <label for="dni">DNI:* </label>
                        <input type="text" name="dni" id="dni"
                               class="label-input-y-75 form-control"
                               value="{{ old('dni', $profesor->dni) }}"
                               placeholder="Ej: 12345678" maxlength="10">
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de nacimiento: </label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                               class="label-input-y-75 form-control"
                               value="{{ old('fecha_nacimiento', optional($profesor->fecha_nacimiento)->format('Y-m-d')) }}"
                               placeholder="dd/mm/aaaa">
                    </div>
                    <div class="form-group">
                        <label for="estado_civil">Estado civil: </label>
                        <select name="estado_civil" id="estado_civil" class="label-input-y-75 form-control">
                            <option value="">Seleccione...</option>
                            <option value="0" {{ old('estado_civil', $profesor->estado_civil) == '0' ? 'selected' : '' }}>Soltero</option>
                            <option value="1" {{ old('estado_civil', $profesor->estado_civil) == '1' ? 'selected' : '' }}>Casado</option>
                            <option value="2" {{ old('estado_civil', $profesor->estado_civil) == '2' ? 'selected' : '' }}>Divorciado</option>
                            <option value="3" {{ old('estado_civil', $profesor->estado_civil) == '3' ? 'selected' : '' }}>Viudo</option>
                            <option value="4" {{ old('estado_civil', $profesor->estado_civil) == '4' ? 'selected' : '' }}>Cónyuge</option>
                            <option value="5" {{ old('estado_civil', $profesor->estado_civil) == '5' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </fieldset>

                {{-- Dirección --}}
                <fieldset>
                    <legend>Dirección</legend>
                    <div class="form-group">
                        <label for="ciudad">Ciudad: </label>
                        <input type="text" name="ciudad" id="ciudad"
                               class="label-input-y-75 form-control"
                               value="{{ old('ciudad', $profesor->ciudad) }}"
                               placeholder="Ej: 9 de julio" maxlength="30">
                    </div>
                    <div class="form-group">
                        <label for="codigo_postal">Código postal: </label>
                        <input type="text" name="codigo_postal" id="codigo_postal"
                               class="label-input-y-75 form-control"
                               value="{{ old('codigo_postal', $profesor->codigo_postal) }}"
                               placeholder="Ej: 6500" maxlength="10">
                    </div>
                    <div class="form-group">
                        <label for="calle">Calle: </label>
                        <input type="text" name="calle" id="calle"
                               class="label-input-y-75 form-control"
                               value="{{ old('calle', $profesor->calle) }}"
                               placeholder="Ej: Av. Eva Perón" maxlength="30">
                    </div>
                    <div class="form-group">
                        <label for="casa_numero">Número de casa: </label>
                        <input type="text" name="casa_numero" id="casa_numero"
                               class="label-input-y-75 form-control"
                               value="{{ old('casa_numero', $profesor->casa_numero) }}"
                               placeholder="Ej: 742" maxlength="4">
                    </div>
                    <div class="form-group">
                        <label for="dpto">Dpto: </label>
                        <input type="text" name="dpto" id="dpto"
                               class="label-input-y-75 form-control"
                               value="{{ old('dpto', $profesor->dpto) }}"
                               placeholder="Ej: A" maxlength="5">
                    </div>
                    <div class="form-group">
                        <label for="piso">Piso: </label>
                        <input type="text" name="piso" id="piso"
                               class="label-input-y-75 form-control"
                               value="{{ old('piso', $profesor->piso) }}"
                               placeholder="Ej: 3" maxlength="15">
                    </div>
                </fieldset>

                {{-- Académico --}}
                <fieldset>
                    <legend>Académico</legend>
                    <div class="form-group">
                        <label for="formacion_academica">Formación académica:* </label>
                        <input type="text" name="formacion_academica" id="formacion_academica"
                               class="label-input-y-75 form-control"
                               value="{{ old('formacion_academica', $profesor->formacion_academica) }}"
                               placeholder="Ej: Profesorado en Matemática" maxlength="150">
                    </div>
                    <div class="form-group">
                        <label for="anio_ingreso">Año de ingreso:* </label>
                        <input type="text" name="anio_ingreso" id="anio_ingreso"
                               class="label-input-y-75 form-control"
                               value="{{ old('anio_ingreso', $profesor->anio_ingreso) }}"
                               placeholder="Ej: 2020" maxlength="4">
                    </div>
                </fieldset>

                {{-- Contacto --}}
                <fieldset>
                    <legend>Contacto</legend>
                    <div class="form-group">
                        <label for="email">Email:* </label>
                        <input type="email" name="email" id="email"
                               class="label-input-y-75 form-control"
                               value="{{ old('email', $profesor->email) }}"
                               placeholder="ejemplo@dominio.com" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="telefono1">Teléfono 1:* </label>
                        <input type="text" name="telefono1" id="telefono1"
                               class="label-input-y-75 form-control"
                               value="{{ old('telefono1', $profesor->telefono1) }}"
                               placeholder="Ej: 2317-876544" maxlength="30">
                    </div>
                    <div class="form-group">
                        <label for="telefono2">Teléfono 2: </label>
                        <input type="text" name="telefono2" id="telefono2"
                               class="label-input-y-75 form-control"
                               value="{{ old('telefono2', $profesor->telefono2) }}"
                               placeholder="Ej: 2317-876543" maxlength="30">
                        <small class="text-muted">Ejemplo: 2317-876543</small>
                    </div>
                </fieldset>

                {{-- Vinculación --}}
                <fieldset>
                    <legend>Vinculación</legend>

                    <h3 class="mb-3"> Vinculaciones actuales</h3>

                    @if($profesor->asignaturas->isEmpty())
                        <p class="text-muted">Este profesor aún no tiene asignaturas vinculadas.</p>
                    @else
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Carrera</th>
                                        <th>Asignatura</th>
                                        <th>Año</th>
                                        <th>Módulo</th>
                                        <th>Carga horaria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profesor->asignaturas as $asignatura)
                                        @php
                                            $pivot = $asignatura->pivot;
                                            $carrera = \App\Models\Carrera::find($pivot->id_carrera);
                                        @endphp
                                        <tr>
                                            <td>{{ $carrera?->nombre ?? '—' }}</td>
                                            <td>{{ $asignatura->nombre }}</td>
                                            <td>{{ $pivot->anio }}</td>
                                            <td>{{ $pivot->tipo_modulo }}</td>
                                            <td>{{ $pivot->carga_horaria }} hs</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <button type="button" class="btn btn-outline-primary mb-3" 
                        onclick="document.getElementById('bloqueVinculacionNueva').style.display = 'block'">
                        Agregar nueva vinculación
                    </button>

                    <div id="bloqueVinculacionNueva" style="display: none;">
                        @include('components.vinculacion-profesor', ['carreras' => $carreras, 'profesor' => $profesor])
                    </div>
                </fieldset>

                {{-- Otros --}}
                <fieldset>
                    <legend>Otros</legend>
                    <div class="form-group">
                        <label for="observaciones">Observaciones: </label>
                        <textarea name="observaciones" id="observaciones"
                                  class="label-input-y-75 form-control"
                                  placeholder="Notas adicionales sobre el profesor/a" maxlength="150">{{ old('observaciones', $profesor->observaciones) }}</textarea>
                    </div>
                </fieldset>

                {{-- Botón enviar --}}
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>

            </form>
        </div>

        {{-- Botón eliminar --}}
        <div class="boton-eliminar mt-4">
            <form method="POST" id="form-eliminar-{{ $profesor->id }}" action="{{ route('admin.profesores.destroy', ['profesor' => $profesor->id]) }}">
                @csrf
                @method('DELETE')
                <button type="button" class="btn_red_outline"
                    onclick="openGeneralModal(
                        'form-eliminar-{{ $profesor->id }}',
                        '¿Estás seguro de que querés eliminar al profesor: {{ mb_strtoupper($profesor->apellido, 'UTF-8') }} {{ mb_strtoupper($profesor->nombre, 'UTF-8') }}?\\n\\nESTA ACCIÓN NO SE PUEDE DESHACER.'
                    )">
                    <i class="ti ti-trash" style="font-size: 1.3em;"></i> Eliminar profesor/a
                </button>
            </form>
        </div>

        {{-- Tabla Mesas --}}
        <div class="table mt-4">
            <div class="table__header">
                <h2>Próximas mesas</h2>
            </div>
            <table class="table__body">
                <thead>
                    <tr>
                        <th>Asignatura</th>
                        <th>Fecha</th>
                        <th>Rol</th>
                        <th class="center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mesas as $mesa)
                        <tr>
                            <td>{{ $mesa->asignatura->nombre }}</td>
                            <td>{{ $formatoFecha->dmhm($mesa->fecha) }}</td>
                            <td>
                                @if ($mesa->prof_presidente == $profesor->id)
                                    Presidente
                                @elseif ($mesa->prof_vocal_1 == $profesor->id)
                                    Vocal 1
                                @elseif ($mesa->prof_vocal_2 == $profesor->id)
                                    Vocal 2
                                @endif
                            </td>
                            <td class="flex just-center">
                                <a href="{{ route('admin.mesas.edit', ['mesa' => $mesa->id]) }}">
                                    <button class="btn_blue" type="button">
                                        <i class="ti ti-file-info"></i> Detalles
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
