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
                <select name="anio" class="campo_info rounded" wire:model.change="anio">
                    <option value="{{ $anio }}">
                        @if ($anio == null)
                            ingrese el año de asignatura a buscar
                        @else
                            {{ $anio }}º año
                        @endif
                    </option>
                    <option value="1">1º año</option>
                    <option value="2">2º año</option>
                    <option value="3">3º año</option>
                    <option value="4">4º año</option>
                    <option value="5">5º año</option>
                </select>
            </div>
            <div class="perfil_dataname">
                <label for="selectedId">Asignatura:</label>
                <select name="id_asignatura" id="selectedId" class="campo_info rounded" wire:model.change="selectedId"
                    form="add_asignatura">
                    @foreach ($asignaturas->where('anio', $anio) as $selectedId)
                        <option value="{{ $selectedId->id }}">
                            {{ $selectedId->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Carga horaria --}}
            <div class="perfil_dataname">
                <label>Cantidad de modulos:</label>
                <input class="campo_info rounded" name="carga_horaria" wire:model="carga_horaria"
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
