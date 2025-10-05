<div>
    <button x-on:click="$wire.showModal = true" class="btn_blue">
        <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i> Agregar Correlativa
    </button>
    <div wire:show="showModal">
        <div>
            <div>
                <span class="close" x-on:click="$wire.showModal = false">&times;</span>
                <h2>Agregar Correlativa</h2>
            </div>
            <div>
                <div class="form-group">
                    <label for="correlativa">Correlativa:</label>
                    <select name="correlativa" id="correlativa" wire:model="correlativa">
                        @foreach ($carrera->asignaturas()->wherePivot('anio', '<', $singleAsignatura->carrera->where('id', $carrera->id)->first()->pivot->anio)->get() as $asignatura)
                            <option value="{{ $asignatura }}">{{ $asignatura->nombre }}</option>
                        @endforeach
                    </select>
                    <label for="correlativas">Agregadas:</label>
                    @foreach ($correlativas as $cor)
                        <div>
                            {{ $cor['nombre'] }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div>
                <button class="btn_blue" wire:click="addCorrelativa">Agregar</button>
                <button class="btn_blue" x-on:click="$wire.showModal = false">Cerrar</button>
            </div>
            <form method="post"
                action="{{ route('admin.correlativa.agregar', ['asignatura' => $singleAsignatura->id, 'carrera' => $carrera->id]) }}">
                @csrf
                <input type="hidden" name="correlativas" value="{{ json_encode($correlativas) }}">
                <button type="submit" class="btn_blue">Guardar</button>
            </form>

        </div>
    </div>
</div>
