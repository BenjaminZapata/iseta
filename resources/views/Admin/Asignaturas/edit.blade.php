@extends('Admin.template')

@section('content')

    <style>
        .perfil_dataname {
            border-bottom: none;
            /* Elimina la línea negra inferior */
            border-radius: 4px;
            padding: 6px 10px;
            box-shadow: none;
        }

        .perfil_dataname input {
            border: 1px solid black
        }
    </style>
    <div class="edit-form-container">
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ASIGNATURAS'])

            <div class="perfil__header">
                <h2>Asignatura</h2>
            </div>

            <div class="perfil__info">
                <?= $form->generate(route('admin.asignaturas.update', ['asignatura' => $asignatura->id]), 'put', [
                        'Información' => [
                            '<div class="perfil_dataname">
                                                                                                                    <label>Asignatura:</label>
                                                                                                                    <input class="campo_info rounded" value="' .
                        e($asignatura->nombre) .
                        '" name="nombre">
                                                                                                                </div>',

                            '<div class="perfil_dataname">
                                                                                                                    <label>Carrera:</label>
                                                                                                                    <span class="campo_info2">' .
                            e($asignatura->carrera->first()?->nombre ?? 'N/A') .
                            '</span>
                                                                                                                </div>',

                            '<div class="perfil_dataname">
                                                                                                                    <label>Tipo módulo:</label>
                                                                                                                    <select class="campo_info rounded" name="tipo_modulo">
                                                                                                                        <option ' .
                            ($asignatura->tipo_modulo == 1 ? 'selected' : '') .
                            ' value="1">Modulos</option>
                                                                                                                        <option ' .
                            ($asignatura->tipo_modulo == 2 ? 'selected' : '') .
                            ' value="2">Horas</option>
                                                                                                                    </select>
                                                                                                                </div>',

                            '<div class="perfil_dataname">
                                                                                                                    <label>Carga horaria:</label>
                                                                                                                    <input class="campo_info rounded" value="' .
                        e($asignatura->carga_horaria) .
                        '" name="carga_horaria">
                                                                                                                </div>',

                            '<div class="perfil_dataname">
                                                                                                                    <label>Año:</label>
                                                                                                                    <input class="campo_info rounded" value="' .
                        e($asignatura->anio) .
                        '" name="anio">
                                                                                                                </div>',

                            '<div class="perfil_dataname">
                                                                                                                    <label>Observaciones:</label>
                                                                                                                    <input class="campo_info rounded" value="' .
                        e($asignatura->observaciones) .
                        '" name="observaciones">
                                                                                                                </div>',

                            $form->texthidden(url()->previous()),
                        ],
                    ]) ?>
                <div class="botones-derecha">
                    {{-- ---------------- BOTÓN ELIMINAR (solo si no está en modo seguro) ---------------- --}}
                    @if (!$config['modo_seguro'])
                        <form id="form-eliminar-{{ $asignatura->id }}"
                            action="{{ route('admin.asignaturas.destroy', $asignatura->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="openGeneralModal('form-eliminar-{{ $asignatura->id }}',
                                    '¿Estás seguro de que querés eliminar a la asignatura: {{ strtoupper($asignatura->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                class="btn_red_outline">
                                <i class="ti ti-trash" style="font-size: 1.3em; margin-right: 8px;"></i>Eliminar asignatura
                            </button>
                        </form>
                    @endif
                </div>



            </div>




            </form>


            {{-- ---------------- BOTÓN ELIMINAR (solo si no está en modo seguro) ---------------- --}}
           
    <form id="form-eliminar-{{ $asignatura->id }}"
          action="{{ route('admin.asignaturas.destroy', $asignatura->id) }}"
          method="POST"
          style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="button"
            onclick="openGeneralModal(
                'form-eliminar-{{ $asignatura->id }}',
                '¿Estás seguro de que querés eliminar la asignatura: {{ strtoupper($asignatura->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.'
            )"
            class="btn_red_outline" style="margin-left: 10px;">
            <i class="ti ti-trash" style="font-size: 1.3em; margin-right: 8px;"></i>
            Eliminar asignatura
        </button>
    </form>



        </div>
    </div>

    {{-- ---------------- CORRELATIVAS ---------------- --}}
    @if ($asignatura->anio > 0 && $asignatura->carrera->isNotEmpty())
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Correlativas</h2>
            </div>
            <div class="matricular">
                @foreach ($asignatura->correlativas as $correlativa)
                    <div class="flex items-center">
                        <li>{{ $correlativa->asignatura->nombre }}</li>
                    </div>
                    @if (!$config['modo_seguro'])
                        <form method="post" class="form-eliminar"
                            action="{{ route('correlativa.eliminar', ['asignatura' => $asignatura->id, 'asignatura_correlativa' => $correlativa->asignatura->id]) }}">
                            @csrf
                            @method('delete')
                            <div class="flex items-center m-1 mx-4">
                                <button class="btn_red"><i class="ti ti-backspace"></i>Quitar</button>
                            </div>
                        </form>
                    @endif
                @endforeach

                <form method="post" action="{{ route('correlativa.agregar', ['asignatura' => $asignatura->id]) }}">
                    @csrf
                    <div class="perfil_dataname1">
                        <label>Materia:</label>
                        <select class="campo_info rounded" id="asignatura_select" name="id_asignatura">
                            @foreach ($asignatura->carrera->first()->asignaturas->where('anio', '<=', $asignatura->anio) as $asignatura_carrera)
                                @if ($asignatura_carrera->id != $asignatura->id)
                                    <option value="{{ $asignatura_carrera->id }}">{{ $asignatura_carrera->nombre }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="upd">
                        <button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ---------------- ALUMNOS CURSANDO ---------------- --}}
    <div class="table">
        <div class="perfil__header-alt just-between">
            <p>Alumnos que cursan esta materia que aun no tienen un estado final (aprobado o desaprobado)</p>
            <a class="btn_blue" href="/admin/cursantes/{{ $asignatura->id }}">
                <i class="ti ti-file-download"></i>Exportar cursadas
            </a>
        </div>

        <table class="table__body">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Condición</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asignatura->cursadas as $cursada)
                    <tr>
                        <td>{{ $cursada->alumno->apellidoNombre() }}</td>
                        <td>{{ $cursada->alumno->dni }}</td>
                        <td>{{ $cursada->condicionString() }}</td>
                        <td>
                            <a href="{{ route('admin.cursadas.edit', ['cursada' => $cursada->id]) }}">
                                <button class="btn_blue"><i class="ti ti-edit"></i>Editar</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </div>
@endsection
