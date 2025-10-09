<div>
    <div class="perfil_one br">
        <div class="perfil__header">
            <legend class="font-600 font-7 white">
                Correlativas de "{{ $singleAsignatura->nombre }}"
            </legend>
        </div>
        <ul>
            @foreach ($correlativasSA as $corr)
                <li class="flex items-center justify-between mb-2" style="margin: 15px; font-size: 1em;">
                    <span>{{ $corr['nombre'] }}</span>
                    <button class="btn_desvincular btnEliminar" style="margin-left: 15px" type="button"
                        wire:click="desvincularCorrelativa({{ $corr['id'] }})">
                        <i class="ti ti-x" style="font-size:0.8em;"></i>
                    </button>
                </li>
            @endforeach
        </ul>
        <p wire:show="hasCorr" class="archivo-vacio mt-2" style="margin: 20px; font-size: 1em;">
            No tiene correlativas asignadas.
        </p>

    </div>
    <div>
        <button x-on:click="$wire.showModal = true" class="btn_blue" style="margin: 20px">
            <i class="ti ti-file-plus" style="font-size: 1.3em; margin-right: 8px;"></i> Agregar Correlativa
        </button>
    </div>
    <div wire:show="showModal" class="acciones-correlativas mt-2">
        <div class="perfil__info" style="background: #ececec">
            <div class="botones-derecha">
                <button class="btn_desvincular2" x-on:click="$wire.showModal = false"><i class="ti ti-x"
                        style="font-size: 1.3em; "></i></button>
            </div>

            <fieldset class="grid-2 p-2" style="margin: 10px;">

                <div class="gap-2 p-0 center">
                    <legend class="font-600 font-7 black" for="correlativa">Correlativa:</legend>
                    <select class="campo_info rounded" name="correlativa" id="correlativa" wire:model="correlativa">
                        <option value="">Seleccioná una correlativa</option>
                        @foreach ($carrera->asignaturas()->wherePivot('anio', '<', $singleAsignatura->carrera->where('id', $carrera->id)->first()->pivot->anio)->get() as $asignatura)
                            @if (!in_array($asignatura->id, $correlativasId))
                                <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div style="margin-left: 10px">
                    <legend class="font-600 font-7 black" for="correlativa">AGREGADAS</legend>
                    @foreach ($correlativas as $index => $cor)
                        <div>
                            <p>{{ $cor['nombre'] }} <span class="close" style="margin-right: 5px"
                                    wire:click="deleteCorrelativa({{ $cor['id'] }})">
                                    &times;</span></p>
                        </div>
                    @endforeach

                </div>
                <button class="btn_blue" wire:click="addCorrelativa"><i class="ti ti-circle-plus"
                        style="font-size: 1.3em; margin-right: 8px;"></i> Agregar
                </button>
            </fieldset>
            <div class="botones-derecha">
                <button type="button" class="btn_blue" style="margin: 15px" wire:click="saveCorrelativas"><i
                        class="ti ti-device-floppy" style="font-size: 1.3em; margin-right: 8px;"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>
