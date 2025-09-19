<fieldset class="p-2" style="margin:10px;">
    <legend class="font-600 font-7">Carreras</legend>
    <div class="grid-2 gap-2 p-0">
        <label class="label-input-y-75">Agregar carrera:
            <select x-on:change="$wire.agregarInscripcion($event.target.value)">
                <option value="">Seleccione...</option>
                @foreach($todasCarreras as $carrera)
                    @if(!in_array($carrera->id, $carrerasSeleccionadas))
                        <option value="{{ $carrera }}">{{ $carrera->nombre }}</option>
                    @endif
                @endforeach
            </select>
        </label>
    </div>
    <ul>
        @foreach($carrerasSeleccionadas as $c)
            <li>
                {{$c['carrera_nombre'] }}
                <button type="button" wire:click="eliminarCarrera({{ $c->id_carrera }})">Eliminar</button>
            </li>
        @endforeach
    </ul>
</fieldset>
