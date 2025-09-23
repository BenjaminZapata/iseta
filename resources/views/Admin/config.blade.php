@extends('Admin.template')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Admin/config.css') }}">

    <div class="perfil_one br">

        @include('components.header-avatar', ['tituloSeccion' => 'CONFIGURACIÓN'])

        <div class="perfil__header">
            <h2 class="perfil__title">GENERAL</h2>
        </div>

        <div class="perfil__info config">

            <form class="flex-col gap-2" method="POST" action="{{ route('admin.config.set') }}">
                @csrf

                {{-- ================== 📊 CONFIGURACIÓN GENERAL ================== --}}

                <fieldset>
                    <div class="form-group">
                        <label>Filas por tabla</label>
                        <input class="campo_info" type="number" name="filas_por_tabla"
                            value="{{ old('filas_por_tabla', $configuracion['filas_por_tabla'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Horas hábiles de inscripción</label>
                        <input class="campo_info" type="number" name="horas_habiles_inscripcion"
                            value="{{ old('horas_habiles_inscripcion', $configuracion['horas_habiles_inscripcion'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Horas hábiles de desinscripción</label>
                        <input class="campo_info" type="number" name="horas_habiles_desinscripcion"
                            value="{{ old('horas_habiles_desinscripcion', $configuracion['horas_habiles_desinscripcion'] ?? '') }}">
                    </div>
                </fieldset>
        </div>

        {{-- ================== 🗓️ FECHAS Y CICLO LECTIVO ================== --}}

        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>FECHAS Y CICLO LECTIVO</h2>
            </div>


            <div class="perfil__info config">
                <fieldset>
                    <div class="form-group">
                        <label>Fecha inicial de rematriculación</label>
                        <input class="campo_info" type="date" name="fecha_inicial_rematriculacion"
                            value="{{ old('fecha_inicial_rematriculacion', $configuracion['fecha_inicial_rematriculacion'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Fecha final de rematriculación</label>
                        <input class="campo_info" type="date" name="fecha_final_rematriculacion"
                            value="{{ old('fecha_final_rematriculacion', $configuracion['fecha_final_rematriculacion'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Año de rematriculación</label>
                        <input class="campo_info" type="number" name="anio_remat"
                            value="{{ old('anio_remat', $configuracion['anio_remat'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Año del ciclo actual</label>
                        <input class="campo_info" type="number" name="anio_ciclo_actual"
                            value="{{ old('anio_ciclo_actual', $configuracion['anio_ciclo_actual'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Fecha límite para revertir rematriculación</label>
                        <input class="campo_info" type="date" name="fecha_limite_desrematriculacion"
                            value="{{ old('fecha_limite_desrematriculacion', $configuracion['fecha_limite_desrematriculacion'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Diferencia máxima entre llamados (días)</label>
                        <input class="campo_info" type="number" name="diferencia_llamados"
                            value="{{ old('diferencia_llamados', $configuracion['diferencia_llamados'] ?? '') }}">
                    </div>
                </fieldset>
            </div>

            {{-- ================== 🏫 DATOS DE LA INSTITUCIÓN ================== --}}
            <div class="perfil_one br">
                <div class="perfil__header">
                    <h2>DATOS DE LA INSTITUCIÓN</h2>
                </div>


                <div class="perfil__info config">
                    <fieldset>


                        <div class="form-group">
                            <label>Nombre de la institución</label>
                            <input class="campo_info" name="nombre"
                                value="{{ old('nombre', $configuracion['nombre'] ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label>Correos electrónicos</label>
                            <input class="campo_info" name="correo1"
                                value="{{ old('correo1', $configuracion['correo1'] ?? '') }}">
                            <input class="campo_info" name="correo2"
                                value="{{ old('correo2', $configuracion['correo2'] ?? '') }}">
                            <input class="campo_info" name="correo3"
                                value="{{ old('correo3', $configuracion['correo3'] ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label>Números telefónicos</label>
                            <input class="campo_info" name="telefono1"
                                value="{{ old('telefono1', $configuracion['telefono1'] ?? '') }}">
                            <input class="campo_info" name="telefono2"
                                value="{{ old('telefono2', $configuracion['telefono2'] ?? '') }}">
                            <input class="campo_info" name="telefono3"
                                value="{{ old('telefono3', $configuracion['telefono3'] ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label>Más información</label>
                            <input class="campo_info" name="mas_info1"
                                value="{{ old('mas_info1', $configuracion['mas_info1'] ?? '') }}">
                            <input class="campo_info" name="mas_info2"
                                value="{{ old('mas_info2', $configuracion['mas_info2'] ?? '') }}">
                            <input class="campo_info" name="mas_info3"
                                value="{{ old('mas_info3', $configuracion['mas_info3'] ?? '') }}">
                        </div>
                    </fieldset>
                </div>
            </div>


            {{-- ================== 🔒 SEGURIDAD ================== --}}
            <div class="perfil_one br">
                <div class="perfil__header">
                    <h2>PERMISOS ALUMNOS</h2>
                </div>


                <div class="perfil__info config">
                    <fieldset>

                        <div class="form-group switches">
                            <label>Los alumnos pueden anotarse a mesas</label>
                            <label class="switch">
                                <input type="checkbox" name="alumno_puede_anotarse_mesa" value="1"
                                    @checked($config['alumno_puede_anotarse_mesa'])>
                                <span class="sliderr round"></span>
                            </label>
                        </div>

                        <div class="form-group switches">
                            <label>Los alumnos pueden bajarse de mesas</label>
                            <label class="switch">
                                <input type="checkbox" name="alumno_puede_bajarse_mesa" value="1"
                                    @checked($config['alumno_puede_bajarse_mesa'])>
                                <span class="sliderr round"></span>
                            </label>
                        </div>

                        <div class="form-group switches">
                            <label>Matricularse</label>
                            <label class="switch">
                                <input type="checkbox" name="alumno_puede_anotarse_cursada" value="1"
                                    @checked($config['alumno_puede_anotarse_cursada'])>
                                <span class="sliderr round"></span>
                            </label>
                        </div>

                        <div class="form-group switches">
                            <label>Desmatricularse</label>
                            <label class="switch">
                                <input type="checkbox" name="alumno_puede_bajarse_cursada" value="1"
                                    @checked($config['alumno_puede_bajarse_cursada'])>
                                <span class="sliderr round"></span>
                            </label>
                        </div>

                        <div class="form-group switches">
                            <label>Entrar a cursadas como libres</label>
                            <label class="switch">
                                <input type="checkbox" name="alumno_puede_anotarse_libre" value="1"
                                    @checked($config['alumno_puede_anotarse_libre'])>
                                <span class="sliderr round"></span>
                            </label>
                        </div>

                        <div class="form-group switches">
                            <label>Modo seguro</label>
                            <label class="switch">
                                <input type="checkbox" name="modo_seguro" value="1" @checked($config['modo_seguro'])>
                                <span class="sliderr round"></span>
                            </label>
                            <small>Oculta los botones de eliminación para evitar borrado accidental.</small>
                        </div>
                    </fieldset>
                </div>
            </div>

            {{-- ================== BOTONES ================== --}}
            <div class="botones-derecha">
                <x-btn-cancelar />
                <button type="submit" class="btn_blue">
                    <i class="ti ti-refresh" style="font-size: 1.3em"></i> Actualizar
                </button>
            </div>
            </form>
        </div>
    @endsection
