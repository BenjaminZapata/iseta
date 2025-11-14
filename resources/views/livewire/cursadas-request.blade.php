<tr class="subject-body hidden" id="subjectBody{{ $groupId }}">
    <td colspan="4">
        <table class="inner-table">
            <thead>
                <tr>
                    <th>ALUMNO</th>
                    <th class="center">ESTADO</th>
                    <th class="center">CONDICIÓN</th>
                    <th class="center">ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->Cursadas() as $sub_cursada)
                    <tr>
                        <td class="bold">
                            {{ $sub_cursada->alumno->apellidoNombre() ?? 'Sin alumno' }}
                        </td>
                        <td class="center">{{ $sub_cursada->aprobado() }}</td>
                        <td class="center">{{ $sub_cursada->condicionString() }}</td>
                        <td class="flex just-center" style="min-width: 170px;">
                            <div class="centrar" style=" gap: 10px;">
                                <a
                                    href="{{ route('admin.cursadas.edit', ['cursada' => $sub_cursada->id]) }}">
                                    <button class="btn_blue btn_contraible">
                                        <i class="ti ti-pencil"
                                            style="font-size: 1.3em;"></i>
                                        <span class="btn-text">Editar</span>
                                    </button>
                                </a>

                                @if (!$config['modo_seguro'])
                                <form id="form-eliminar-{{ $sub_cursada->id }}"
                                    action="{{ route('admin.cursadas.destroy', $sub_cursada->id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="openGeneralModal('form-eliminar-{{ $sub_cursada->id }}',
                                            '¿Estás seguro de que querés eliminar la cursada del alumno: {{ strtoupper($sub_cursada->alumno->apellidoNombre()) }} de la asignatura:  {{ strtoupper($sub_cursada->asignatura->nombre ?? 'Sin Asignatura') }} de la carrera {{ strtoupper($sub_cursada->carrera->nombre ?? 'Sin Carrera') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                        class="btn_icon-danger btn_contraible"
                                        style="background-color: red;">
                                        <i class="ti ti-trash"
                                            style="font-size: 1.3em"></i>
                                        <span class="btn-text">Eliminar</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </td>
</tr>