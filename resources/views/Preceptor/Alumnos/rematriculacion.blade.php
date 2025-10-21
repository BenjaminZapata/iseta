@extends('preceptor.template')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/Admin/rematriculacion.css') }}">



    <div class="perfil_one br">
        @include('Preceptor.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ALUMNOS'])
        <button id="ayuda-btn" class="btn-ayuda" title="Información">
            <i class="ti ti-help-circle"></i>
        </button>

        <div id="ayuda-modal" class="modal-ayuda none">
            <div class="modal-content">
                <h3>¿Cómo funciona la rematriculación?</h3>
                <p>Si solo desea registrar que un alumno está inscripto en una carrera sin anotarlo en ninguna cursada, deje
                    todos los campos con el valor "No matricular" y haga click en enviar.</p>
                <p>Al hacer esto el alumno podrá visualizar esta carrera en el seleccionador de carreras y podrá inscribirse
                    a las cursadas manualmente.</p>
                <button id="cerrar-ayuda" class="btn-close">Cerrar</button>
            </div>
        </div>


        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Matricular alumno</h2>
            </div>

            <div class="perfil__info">
                <form method="POST"
                    action="{{ route('admin.alumno.matricular.post', ['alumno' => $alumno->id, 'carrera' => $carrera->id]) }}">
                    @csrf

                    @if (count($asignaturas) <= 0)
                        <div class="alert-box bg-warning p-3 rounded text-center">
                            <p>Este alumno no cuenta con asignaturas para rendir de esta carrera.</p>
                        </div>
                    @else
                        @foreach ($asignaturas as $asignatura)
                            <div
                                class="asignatura-card bg-white p-3 rounded shadow-sm mb-3 @if ($asignatura->equivalencias_previas) border-left-warning @endif">
                                <div class="grid grid-cols-2 gap-6 mb-2">
                                    <div>
                                        <label class="font-semibold">Año:</label>
                                        <span>{{ $asignatura->carrera()->wherePivot('id_carrera', $carrera->id)->first()->pivot->anio + 1 }}</span>
                                    </div>
                                    <div>
                                        <label class="font-semibold">Asignatura:</label>
                                        <a href="{{ route('preceptor.asignaturas.edit', ['asignatura' => $asignatura->id]) }}"
                                            class="asignatura-link text-blue-600 hover:underline"
                                            title="Editar asignatura">{{ $asignatura->nombre }}</a>
                                    </div>
                                </div>



                                <div>
                                    @if ($asignatura->debeCorrelativas($alumno, $carrera->id))
                                        <div class="correlativa-header cursor-pointer flex items-center justify-between"
                                            onclick="toggleEquiv({{ $asignatura->id }})">
                                            <p class="font-semibold text-warning">Debe correlativas</p>
                                            <i class="ti ti-chevron-down chevron icon-{{ $asignatura->id }}"></i>
                                        </div>

                                        <ul class="equiv-list id-{{ $asignatura->id }} pl-4 list-disc text-sm">
                                            @foreach ($asignatura->debeCorrelativas($alumno, $carrera->id) as $equiv)
                                                <li><strong>{{ $equiv->anioStr($carrera->id) }}:</strong>
                                                    {{ $equiv->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <select class="form-select w-full mt-2" name="{{ $asignatura->id }}">
                                            <option value="">No matricular</option>
                                            <option @selected(old($asignatura->id) == 2) value="2">Regular</option>
                                            <option @selected(old($asignatura->id) == 1) value="1">Libre</option>
                                            <option @selected(old($asignatura->id) == 3) value="3">Promoción</option>
                                            <option @selected(old($asignatura->id) == 4) value="4">Equivalencia</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="text-right mt-4"
                            style="display: inline-flex; align-items: center; text-transform: none;">
                            <button class="btn_blue"><i class="ti ti-send" style="margin-right: 8px; font-size: 1.3em;"></i>
                                Matricular</button>

                        </div>
                        <x-btn-cancelar />
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        window.onclick = function(e) {
            if (!e.target.classList.contains('ver-equiv')) return;
            let id = e.target.dataset.element;
            let list = document.querySelector('.id-' + id);
            list.classList.toggle('none');
        }
    </script>

    <script>
        const ayudaBtn = document.getElementById('ayuda-btn');
        const ayudaModal = document.getElementById('ayuda-modal');
        const cerrarAyuda = document.getElementById('cerrar-ayuda');

        ayudaBtn.onclick = () => ayudaModal.classList.toggle('none');
        cerrarAyuda.onclick = () => ayudaModal.classList.add('none');
    </script>

    <script>
        function toggleEquiv(id) {
            const list = document.querySelector('.id-' + id);
            const icon = document.querySelector('.icon-' + id);
            list.classList.toggle('expanded');
            icon.classList.toggle('rotated');
        }
    </script>



@endsection