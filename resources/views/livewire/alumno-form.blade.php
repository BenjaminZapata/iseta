<div x-data="{ step: $wire.entangle('step').live }" x-cloak>

    <!-- Step 1: Crear alumno -->
    <form wire:submit.prevent="siguientePaso" wire:show="step === 1">
        @csrf
        <fieldset class="p-2" style="margin:10px;">
            <legend class="font-600 font-7">Alumno</legend>
            <div class="grid-2 gap-2 p-0">
                <label class="label-input-y-75">Nombre:
                    <input type="text" wire:model="alumno.nombre">
                </label>
                <label class="label-input-y-75">Apellido:
                    <input type="text" wire:model="alumno.apellido">
                </label>
                <label class="label-input-y-75">DNI:
                    <input type="text" wire:model="alumno.dni">
                </label>
                <label class="label-input-y-75">Fecha de nacimiento:
                    <input type="date" wire:model="alumno.fecha_nacimiento" class="p-1 w-75p">
                </label>
                <label class="label-input-y-75">Lugar de nacimiento:
                    <input type="text" wire:model="alumno.lugar_nacimiento">
                </label>
                <label class="label-input-y-75">Estado civil:
                    <select wire:model="alumno.estado_civil">
                        <option value="">Vacio</option>
                        <option value="0">Soltero</option>
                        <option value="1">Casado</option>
                        <option value="2">Divorciado</option>
                        <option value="3">Viudo</option>
                        <option value="4">Conyuge</option>
                        <option value="5">Otro</option>
                    </select>
                </label>
                <label class="label-input-y-75">Género:
                    <select wire:model="alumno.genero">
                        <option value="">Vacio</option>
                        <option value="0">Masculino</option>
                        <option value="1">Femenino</option>
                        <option value="2">Otro</option>
                    </select>
                </label>
            </div>
            </fieldset>

            <!-- Dirección -->
            <fieldset class="p-2" style="margin: 10px;">
                <legend class="font-600 font-7">Dirección</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Ciudad:
                        <input type="text" wire:model="alumno.ciudad">
                    </label>
                    <label class="label-input-y-75">Código postal:
                        <input type="text" wire:model="alumno.codigo_postal">
                    </label>
                    <label class="label-input-y-75">Calle:
                        <input type="text" wire:model="alumno.calle">
                    </label>
                    <label class="label-input-y-75">Altura:
                        <input type="text" wire:model="alumno.casa_numero">
                    </label>
                    <label class="label-input-y-75">Departamento:
                        <input type="text" wire:model="alumno.dpto">
                    </label>
                    <label class="label-input-y-75">Piso:
                        <input type="text" wire:model="alumno.piso">
                    </label>
                </div>
            </fieldset>

            <!-- Contacto -->
            <fieldset class="p-2" style="margin: 10px;">
                <legend class="font-600 font-7">Contacto</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Email:
                        <input type="email" wire:model="alumno.email">
                    </label>
                    <label class="label-input-y-75">Teléfono 1:
                        <input type="text" wire:model="alumno.telefono_1">
                    </label>
                    <label class="label-input-y-75">Teléfono 2:
                        <input type="text" wire:model="alumno.telefono_2">
                    </label>
                    <label class="label-input-y-75">Teléfono 3:
                        <input type="text" wire:model="alumno.telefono_3">
                    </label>
                </div>
            </fieldset>

            <!-- Académico -->
            <fieldset class="p-2" style="margin: 10px;">
                <legend class="font-600 font-7">Académico</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Título anterior:
                        <input type="text" wire:model="alumno.titulo_anterior">
                    </label>
                    <label class="label-input-y-75">Becas:
                        <input type="text" wire:model="alumno.becas">
                    </label>
                    <label class="label-input-y-75">Nombre de institución secundaria:
                        <input type="text" wire:model="alumno.nombre_institucion_secundario">
                    </label>
                    <label class="label-input-y-75">Título secundario:
                        <select wire:model="alumno.titulo_secundario">
                            <option value="">Seleccione una opción</option>
                            <option value="0">Fotocopia del título original secundario</option>
                            <option value="1">Certificado de constancia de título en trámite</option>
                            <option value="2">Constancia de alumno del último año del nivel secundario</option>
                            <option value="3">No entregado</option>
                        </select>
                    </label>
                </div>
            </fieldset>

            <!-- Otros -->
            <fieldset class="p-2" style="margin: 10px;">
                <legend class="font-600 font-7">Otros</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Observaciones:
                        <textarea wire:model="alumno.observaciones"></textarea>
                    </label>
                </div>
            </fieldset>
        </fieldset>
        <button type="submit" class="btn-primary">Siguiente: Carreras</button>
    </form>

    <!-- Step 2: Seleccionar carreras -->
<!-- Step 2: Seleccionar carreras -->
    <div x-show="step === 2">
        <livewire:carreras-form
            :todas-carreras="\App\Models\Carrera::all()"
            :carreras-seleccionadas="$carrerasSeleccionadas"
            :key="'carreras-form'" />
        <button type="button" x-on:click="step=1">Volver</button>
        <button type="button" x-on:click="step=3" class="btn-primary">Siguiente: Confirmar</button>
    </div>
    <!-- Step 3: Confirmación -->
    <div x-show="step === 3">
        <fieldset class="p-2" style="margin:10px;">
            <legend class="font-600 font-7">Confirmación</legend>
            <p><strong>Alumno:</strong> {{ $alumno['nombre'] }} {{ $alumno['apellido'] }}</p>
            <p><strong>Carreras seleccionadas:</strong></p>
            <ul>
                @foreach($carrerasSeleccionadas as $c)
                    <li>{{ \App\Models\Carrera::find($c)->nombre }}</li>
                @endforeach
            </ul>
        </fieldset>
        <button type="button" x-on:click="step=2">Volver</button>
        <button type="button" wire:click="guardarTodo" class="btn-primary">Confirmar y guardar</button>
    </div>

</div>
