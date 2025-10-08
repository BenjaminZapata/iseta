<div>
    <div>
        <button x-on:click="$wire.showModal = true" class="btn_blue" style="margin: 20px">
            <i class="ti ti-file-plus" style="font-size: 1.3em; margin-right: 8px;"></i> Agregar Correlativa
        </button>
    </div>

    <div wire:show="showModal">
        <div class="perfil__info" style="background: #ececec">
            {{-- <div>
                <span class="close" x-on:click="$wire.showModal = false">&times;</span>
                <h2>Agregar Correlativa</h2>
            </div> --}}
            <div class="botones-derecha">
                <button class="btn_desvincular2" x-on:click="$wire.showModal = false"><i class="ti ti-x"
                        style="font-size: 1.3em; "></i></button>
            </div>

            <fieldset class="grid-2 p-2" style="margin: 10px;">

                <div class="gap-2 p-0 center">
                    <legend class="font-600 font-7 black" for="correlativa">Correlativa:</legend>
                    <select class="campo_info rounded" name="correlativa" id="correlativa" wire:model="correlativa">
                        @foreach ($carrera->asignaturas()->wherePivot('anio', '<', $singleAsignatura->carrera->where('id', $carrera->id)->first()->pivot->anio)->get() as $asignatura)
                            <option value="{{ $asignatura }}">{{ $asignatura->nombre }}</option>
                            @endforeach
                    </select>
                </div>
                <div style="margin-left: 10px">
                    <legend class="font-600 font-7 black" for="correlativa">AGREGADAS</legend>
                    @foreach ($correlativas as $cor)
                    <div>
                        <p>{{ $cor['nombre'] }} <span class="close" style="margin-right: 5px"
                                wire:click="deleteCorrelativa({{ $cor['id'] }})">
                                &times;</span></p>
                    </div>
                    @endforeach

                </div>
                <button class="btn_blue" wire:click="addCorrelativa"><i class="ti ti-circle-plus"
                        style="font-size: 1.3em; margin-right: 8px;"></i> Agregar</button>
            </fieldset>

            <form method="post"
                action="{{ route('admin.correlativa.agregar', ['asignatura' => $singleAsignatura->id, 'carrera' => $carrera->id]) }}">
                @csrf
                <div class="botones-derecha">
                    <input type="hidden" name="correlativas" value="{{ json_encode($correlativas) }}">
                    <button type="submit" class="btn_blue" style="margin: 15px"><i class="ti ti-device-floppy"
                            style="font-size: 1.3em; margin-right: 8px;"></i>Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>