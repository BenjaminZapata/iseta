<form method="post" action="{{ route('admin.carreras.addAsignatura') }}" id="add_asignatura">
    @csrf
    <div class="perfil__info">
        <div class="perfil_dataname">
            <label>Carrera:</label>
            <p class="campo_info-noinput rounded"> {{ $carrera->nombre }} </p>
            <input type="hidden" name="carrera_id" value="{{ $carrera->id }}">
        </div>
                <div class="perfil_dataname">
            <label>Año:</label>
            <select type="number" name="anio" wire:model.change="$anio" class="campo_info rounded">
                <option value="{{ $anio }}">
                    @if ($anio  == null )
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
            <select name="asignatura_id" id="selectedId" class="campo_info rounded" wire:model.change="selectedId"
                form="add_asignatura">
                @foreach ($asignaturas as $selectedId)
                    @if ($selectedId->anio == $anio)
                        <option value="{{ $selectedId->id }}">
                            {{ $selectedId->nombre }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="perfil_dataname">
            <label>Tipo módulo:</label>
            <select name="tipo_modulo" class="campo_info rounded" wire:model="$tipo_modulo">
                <option value="1">Módulos</option>
                <option value="2">Horas</option>
            </select>
        </div>

        <div class="perfil_dataname">
            <label>Carga horaria:</label>
            <input name="carga_horaria" class="campo_info rounded" value="{{ $carga_horaria }}" type="number"
                wire:model="$carga_horaria">
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
