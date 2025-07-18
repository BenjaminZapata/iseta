<form method="post" action="{{route('admin.carreras.addAsignatura')}}" id="add_asignatura">
@csrf
    <div class="perfil__info">
        <div class="perfil_dataname">
            <label for="selectedId">Asignatura:</label>
            <select name="asignatura_id" id="selectedId" class="campo_info rounded" wire:model.change="selectedId" form="add_asignatura">
                @foreach($asignaturas as $selectedId)
                    <option value="{{ $selectedId->id }}">
                        {{ $selectedId->nombre }}
                    </option>
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
            <input name="carga_horaria" class="campo_info rounded" value="{{ $carga_horaria }}" type="number" wire:model="$carga_horaria">
        </div>

        <div class="perfil_dataname">
            <label>Año:</label>
            <p class="campo_info-noinput rounded">{{ $anio }}</p>
            <input type="hidden" name="anio" value="{{ $anio }}">
        </div>
        <div class="perfil_dataname">
            <label>Carrera:</label>
            <p class="campo_info-noinput rounded"> {{ $carrera->nombre }} </p>
            <input type="hidden" name="carrera_id" value="{{ $carrera->id }}">
        </div>
        <div class="upd">
            <button class="btn_blue" type="submit">
                <i class="ti ti-circle-plus">
                </i>
                Agregar
            </button>
        </div>
    </div>
</form>
