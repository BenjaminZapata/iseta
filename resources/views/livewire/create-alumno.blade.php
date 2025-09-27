<div x-data="{ step: $wire.entangle('step').live }" x-cloak>

    <!-- Step 1: Crear alumno -->
    <form wire:submit="siguientePaso" x-show="step === 1">
        @csrf

        <!-- Alumno -->
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Alumno</h2>
            </div>
            <fieldset class="p-2">

                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Nombre:
                        <input type="text" wire:model="form.nombre"
                            class="@error('form.nombre') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.nombre')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Apellido:
                        <input type="text" wire:model="form.apellido"
                            class="@error('form.apellido') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.apellido')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">DNI:
                        <input type="text" wire:model="form.dni">
                        <div class="campo-alert">
                            @error('form.dni')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Fecha de nacimiento:
                        <input type="date" wire:model="form.fecha_nacimiento"
                            class="p-1 w-75p @error('form.fecha_nacimiento') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.fecha_nacimiento')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Lugar de nacimiento:
                        <input type="text" wire:model="form.lugar_nacimiento"
                            class="@error('form.lugar_nacimiento') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.lugar_nacimiento')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Estado civil:
                        <select wire:model="form.estado_civil"
                            class="@error('form.estado_civil') border-red-500 @enderror">
                            <option value="">Vacio</option>
                            <option value="0">Soltero</option>
                            <option value="1">Casado</option>
                            <option value="2">Divorciado</option>
                            <option value="3">Viudo</option>
                            <option value="4">Conyuge</option>
                            <option value="5">Otro</option>
                        </select>
                        <div class="campo-alert">
                            @error('form.estado_civil')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Género:
                        <select wire:model="form.genero" class="@error('form.genero') border-red-500 @enderror">
                            <option value="">Vacio</option>
                            <option value="0">Masculino</option>
                            <option value="1">Femenino</option>
                            <option value="2">Otro</option>
                        </select>
                        <div class="campo-alert">
                            @error('form.genero')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                </div>
            </fieldset>

            <!-- Dirección -->
            <fieldset class="p-2">
                <legend class="font-600 font-7">Dirección</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Ciudad:
                        <input type="text" wire:model="ciudad"
                            class="@error('form.ciudad') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.ciudad')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Código postal:
                        <input type="text" wire:model="codigo_postal"
                            class="@error('form.codigo_postal') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.codigo_postal')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Calle:
                        <input type="text" wire:model="calle" class="@error('form.calle') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.calle')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Altura:
                        <input type="text" wire:model="casa_numero"
                            class="@error('form.casa_numero') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.casa_numero')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Departamento:
                        <input type="text" wire:model="dpto" class="@error('form.dpto') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.dpto')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Piso:
                        <input type="text" wire:model="piso" class="@error('form.piso') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.piso')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                </div>
            </fieldset>

            <!-- Contacto -->
            <fieldset class="p-2">
                <legend class="font-600 font-7">Contacto</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Email:
                        <input type="email" wire:model="form.email"
                            class="@error('form.email') is-invalid @else is-valid @enderror">
                        <div class="campo-alert">
                            @error('form.email')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Teléfono 1:
                        <input type="text" wire:model="form.telefono_1"
                            class="@error('form.telefono_1') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.telefono_1')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Teléfono 2:
                        <input type="text" wire:model="form.telefono_2"
                            class="@error('form.telefono_2') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.telefono_2')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                </div>
            </fieldset>

            <!-- Académico -->
            <fieldset class="p-2">
                <legend class="font-600 font-7">Académico</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Título anterior:
                        <input type="text" wire:model="form.titulo_anterior"
                            class="@error('form.titulo_anterior') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.titulo_anterior')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Becas:
                        <input type="text" wire:model="form.becas"
                            class="@error('form.becas') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.becas')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Nombre de institución secundaria:
                        <input type="text" wire:model="form.nombre_institucion_secundario"
                            class="@error('form.nombre_institucion_secundario') border-red-500 @enderror">
                        <div class="campo-alert">
                            @error('form.nombre_institucion_secundario')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                    <label class="label-input-y-75">Título secundario:
                        <select wire:model="form.titulo_secundario"
                            class="@error('form.titulo_secundario') border-red-500 @enderror">
                            <option value="">Seleccione una opción</option>
                            <option value="0">Fotocopia del título original secundario</option>
                            <option value="1">Certificado de constancia de título en trámite</option>
                            <option value="2">Constancia de alumno del último año del nivel secundario</option>
                            <option value="3">No entregado</option>
                        </select>
                        <div class="campo-alert">
                            @error('form.titulo_secundario')
                            {{ $message }}
                            @enderror
                        </div>
                    </label>
                </div>
            </fieldset>
        </div>
        <div class="botones-derecha">
            <x-btn-cancelar />
            <button type="submit" class="btn_blue">
                Siguiente: Carreras<i class="ti ti-chevron-right"
                    style="font-size: 1.3em; margin-left: 8px;"></i></button>
        </div>

    </form>

    <!-- Step 2: Seleccionar carreras + inscripción -->

    <div x-show="step === 2">
        <div x-data="{ show: $wire.entangle('show').live }" x-cloak>
            <!-- Carreras -->
            <fieldset class="p-2">
                <legend class="font-600 font-7">Carreras</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="label-input-y-75">Agregar carrera:
                        <select x-on:change="$wire.agregarInscripcion($event.target.value)" class="input">
                            <option value="">Seleccione...</option>
                            @foreach ($todasCarreras as $carrera)
                            @if (!in_array($carrera->id, $idCarreras))
                            <option value="{{ $carrera }}">{{ $carrera->nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </label>
                </div>

                <ul class="carreras-list">
                    @foreach ($carrerasSeleccionadas as $c)
                    <li>
                        {{ $c['carrera_nombre'] }}
                        <button class="btn_red_outline" type="button"
                            wire:click="eliminarCarrera({{ $c['id_carrera'] }})">
                            <i class="ti ti-trash" style="font-size: 1.3em; margin-right: 8px;"></i>Eliminar
                        </button>
                    </li>
                    @endforeach
                </ul>
            </fieldset>

            <!-- Inscripción -->
            <div x-show="show">
                <form wire:submit="guardarInscripcion" class="p-3 border rounded bg-gray-50">
                    <h3 class="font-bold mb-2">Datos de inscripción</h3>

                    <label class="block mb-2">
                        Año de inscripción:
                        <input type="number" wire:model.fill="iForm.anio_inscripcion" class="input"
                            value="{{ now()->year }}">
                        @error('iForm.anio_inscripcion')
                        <div class="campo-alert">{{ $message }}</div>
                        @enderror
                    </label>

                    <label class="block mb-2">
                        Índice libro matriz:
                        <input type="text" wire:model="iForm.indice_libro_matriz" class="input">
                        @error('iForm.indice_libro_matriz')
                        <div class="campo-alert">{{ $message }}</div>
                        @enderror
                    </label>

                    <label class="block mb-2">
                        Año de finalización:
                        <input type="number" wire:model="iForm.anio_finalizacion" class="input">
                        @error('iForm.anio_finalizacion')
                        <div class="campo-alert">{{ $message }}</div>
                        @enderror
                    </label>
                    <input type="hidden" wire:model="iForm.estado">
                    <div class="mt-3 flex gap-2">
                        <button type="button" wire:click="$set('show', false)"
                            class="btn_cancelar">Cancelar</button>
                        <button type="submit" class="btn_blue">Guardar inscripción</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="botones-create">
            <button type="button" x-on:click="step=1" class="btn_blue">Volver</button>
            <button type="button" x-on:click="step=3" class="btn_blue">Siguiente: Confirmar</button>
        </div>
    </div>

    <!-- Step 3: Confirmación -->
    <div x-show="step === 3">
        <fieldset class="p-2">
            <legend class="font-600 font-7">Confirmación</legend>
            <p><strong>Alumno:</strong> {{ $alumno['apellido'] ?? '' }} {{ $alumno['nombre'] ?? '' }}</p>
            <p><strong>Carreras seleccionadas:</strong></p>
            <ul class="carreras-list">
                @foreach ($carrerasSeleccionadas as $c)
                <li>{{ $c['carrera_nombre'] }}</li>
                @endforeach
            </ul>
        </fieldset>
        <div class="botones-create">
            <button type="button" x-on:click="step=2" class="btn_blue">Volver</button>
            <button type="button" wire:click="guardarTodo" class="btn_blue">Confirmar y guardar</button>
        </div>
    </div>

</div>