<div class="perfil__info">
    <div class="perfil_dataname">
        <label for="selectedId">Asignatura:</label>
        <select id="selectedId" class="campo_info rounded" wire:model.change="selectedId">
            @foreach($asignaturas as $asignatura)
                <option value="{{ $asignatura->id }}">
                    {{ $asignatura->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="perfil_dataname">
        <label>Tipo módulo:</label>
        <select name="tipo_modulo" class="campo_info rounded" value="" wire:model="$tipo_modulo">
            <option value="1">Módulos</option>
            <option value="2">Horas</option>
        </select>
    </div>

    <div class="perfil_dataname">
        <label>Carga horaria:</label>
        <input name="carga_horaria" class="campo_info rounded" value="{{ $carga_horaria }}" type="number" wire:model="$carga_horaria">
    </div>

    <div class="perfil_dataname">
        <label>Año:</label>
        <p class="campo_info-noinput rounded">{{ $anio }}</p>
    </div>
</div>
