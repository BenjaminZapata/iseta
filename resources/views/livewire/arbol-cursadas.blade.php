<tbody>
    {{-- NIVEL 1: Carrera --}}
    <tr>
        <td><strong>{{ $primera->carrera->nombre ?? 'Sin carrera' }}</strong></td>
        <td class="center">{{ $primera->anio_cursada }}</td>
        <td class="center">
            <div class="centrar">
                <button type="button" 
                        class="btn_blue career-summary" 
                        data-target="#careerBody{{ $idCarreraAnio }}">
                    <i class="ti ti-folder iconos"></i> Ver asignaturas
                </button>
            </div>
        </td>
    </tr>

    {{-- NIVEL 2: Asignaturas --}}
    <tr class="career-body hidden" id="careerBody{{ $idCarreraAnio }}">
        <td colspan="4">
            <table class="inner-table">
                <tbody>
                    @foreach ($this->Asignaturas as $asignatura)
                        @php
                            $groupId = "{$grupo[0]->id_carrera}-{$asignatura->id}-{$grupo[0]->anio_cursada}";
                        @endphp

                        <tr class="subject-summary" wire:key="asig-{{ $groupId }}">
                            <td style="padding-left: 40px;">
                                {{ $asignatura->nombre ?? 'Sin asignatura' }}
                            </td>
                            <td class="flex just-center" style="min-width: 200px">
                                <div class="centrar" style="gap: 10px">
                                    <div>
                                        <button class="btn_blue subject-toggle"
                                            data-target="#subjectBody{{ $groupId }}">
                                            <i class="ti ti-users iconos"></i> Ver alumnos
                                        </button>
                                    </div>
                                    <a href="{{ route('admin.cursadas.registroAcademico', ['cursada_group' => $groupId]) }}"
                                        class="btn_blue" onclick="event.stopPropagation();">
                                        <i class="ti ti-file-export iconos"></i>
                                        Registro de Avance
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- NIVEL 3: Alumnos (subcomponente Livewire) --}}
                        <tr class="subject-body hidden" id="subjectBody{{ $groupId }}">
                            <td colspan="4">
                                <livewire:cursadas-request 
                                    :key="'req-'.$groupId" 
                                    :groupId="$groupId" 
                                    :id_carrera="$grupo[0]->id_carrera" 
                                    :anio_cursada="$grupo[0]->anio_cursada" 
                                    :asignatura="$asignatura" 
                                    lazy />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</tbody>