<form method="post" action="{{ route('admin.carreras.addAsignatura', ['carrera' => $carrera]) }}" id="add_asignatura">
    @csrf
    <div x-data="{ anio: $wire.entangle('anio').live }">
        <div class="perfil__info">
            <div class="perfil_dataname">
                <label>Carrera:</label>
                <p class="campo_info-noinput rounded"> {{ $carrera->nombre }} </p>
                <input type="hidden" name="id_carrera" value="{{ $carrera->id }}">
            </div>
            <div class="perfil_dataname">
    <label>Año:</label>
    <select name="anio" class="campo_info rounded" wire:model="anio" @if($selectedId) disabled @endif>
        @if (is_null($anio))
            <option value="">Elija el año de la asignatura</option>
        @endif

        @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}" {{ $anio == $i ? 'selected' : '' }}>
                {{ $i }}º año
            </option>
        @endfor
    </select>
</div>


            <div class="perfil_dataname">
                <label for="selectedId">Asignatura:</label>
                <select name="id_asignatura" id="selectedId" class="campo_info rounded" wire:model="selectedId" form="add_asignatura">
                    <option value="">Seleccione una asignatura</option>
                    @foreach ($asignaturas as $asig)
                    @if($asig->id != ($carrera->asignaturas->first()->id ?? null))
                    <option value="{{ $asig->id }}">{{ $asig->nombre }}</option>
                    @endif
                    @endforeach
                </select>
            </div>


            {{-- Carga horaria --}}
            <div class="perfil_dataname">
                <label>Cantidad de modulos:</label>
                <input class="campo_info rounded" name="carga_horaria" wire:model.fill="carga_horaria"
                    value="{{ old('carga_horaria') }}">

            </div>
        </div>
    </div>
    <div class="botones-derecha"
        style="margin-right: 27px; padding-top: 10px; padding-bottom: 16px; display: flex; gap: 12px; justify-content: flex-end;">

        <x-btn-cancelar />
        <button type="submit" class="btn_blue">
            <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
            Agregar
        </button>
    </div>
</form>