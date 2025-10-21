<div x-data="{ step: $wire.entangle('step').live }" x-cloak>


    <!-- Step 1: Crear alumno -->
    <form wire:submit="siguientePaso" x-show="step === 1" id="form-paso-1">
        @csrf

        <!-- Alumno -->
        <p class="info-obligatorios">
            Los campos marcados con <span style="color:red">*</span> son obligatorios.
        </p>

        <fieldset class="p-2" style="margin: 10px">
            <legend class="font-600 font-7">Alumno</legend>
            <div class="grid-2 gap-2 p-0">
                <label class="label-input-y-75">Nombre:*
                    <input type="text" wire:model="form.nombre" placeholder="Ej: Juan"
                        class="@error('form.nombre') input-error border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.nombre')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Apellido:*
                    <input type="text" wire:model="form.apellido" placeholder="Ej: Pérez"
                        class="@error('form.apellido') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.apellido')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">DNI:*
                    <input type="text" wire:model="form.dni" placeholder="Ej: 12345678"
                        class="@error('form.dni') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.dni')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Fecha de nacimiento:*
                    <input type="date" wire:model="form.fecha_nacimiento" placeholder="Formato: dd/mm/aaaa"
                        class="p-1 w-75p @error('form.fecha_nacimiento') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.fecha_nacimiento')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Ciudad de nacimiento:
                    <input type="text" wire:model="form.lugar_nacimiento" placeholder="Ej: Córdoba"
                        class="@error('form.lugar_nacimiento') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.lugar_nacimiento')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Estado civil:
                    <select wire:model="form.estado_civil" class="@error('form.estado_civil') border-red-500 @enderror">
                        <option value="">Seleccione una opción</option>
                        <option value="0">Soltero</option>
                        <option value="1">Casado</option>
                        <option value="2">Divorciado</option>
                        <option value="3">Viudo</option>
                        <option value="4">Cónyuge</option>
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
                        <option value="">Seleccione una opción</option>
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
        <fieldset class="p-2" style="margin: 10px">
            <legend class="font-600 font-7">Dirección</legend>
            <div class="grid-2 gap-2 p-0">
                <label class="label-input-y-75">Ciudad:
                    <input type="text" wire:model="form.ciudad" placeholder="Ej: 9 de Julio"
                        class="@error('form.ciudad') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.ciudad')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Código postal:
                    <input type="text" wire:model="form.codigo_postal" placeholder="Ej: 6500 "
                        class="@error('form.codigo_postal') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.codigo_postal')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Calle:
                    <input type="text" wire:model="form.calle" placeholder="Ej: Av. Eva Perón"
                        class="@error('form.calle') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.calle')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Altura:
                    <input type="text" wire:model="form.casa_numero" placeholder="Ej: 742"
                        class="@error('form.casa_numero') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.casa_numero')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Departamento:
                    <input type="text" wire:model="form.dpto" placeholder="Ej: A"
                        class="@error('form.dpto') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.dpto')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Piso:
                    <input type="text" wire:model="form.piso" placeholder="Ej: 3"
                        class="@error('form.piso') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.piso')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
            </div>
        </fieldset>

        <!-- Contacto -->
        <fieldset class="p-2" style="margin: 10px">
            <legend class="font-600 font-7">Contacto</legend>
            <div class="grid-2 gap-2 p-0">
                <label class="label-input-y-75">Email:*
                    <input type="email" wire:model="form.email" placeholder="Ej: ejemplo@dominio.com"
                        class="@error('form.email') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.email')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Teléfono 1:*
                    <input type="text" wire:model="form.telefono1" placeholder="Ej: 2317-876544"
                        class="@error('form.telefono1') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.telefono1')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Teléfono 2:
                    <input type="text" wire:model="form.telefono2" placeholder="Ej: 2317-876543"
                        class="@error('form.telefono2') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.telefono2')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
            </div>
        </fieldset>

        <!-- Académico -->
        <fieldset class="p-2" style="margin: 10px">
            <legend class="font-600 font-7">Académico</legend>
            <div class="grid-2 gap-2 p-0">
                <label class="label-input-y-75">Título anterior:
                    <input type="text" wire:model="form.titulo_anterior" placeholder="Ej: Técnico en Informática"
                        class="@error('form.titulo_anterior') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.titulo_anterior')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Becas:
                    <input type="text" wire:model="form.becas" placeholder="Ej: 2"
                        class="@error('form.becas') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.becas')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Institución secundaria:
                    <input type="text" wire:model="form.nombre_institucion_secundario"
                        placeholder="Ej: Escuela Nacional N°1"
                        class="@error('form.nombre_institucion_secundario') border-red-500 @enderror">
                    <div class="campo-alert">
                        @error('form.nombre_institucion_secundario')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
                <label class="label-input-y-75">Título secundario:*
                    <select wire:model="form.titulo_secundario"
                        class="@error('form.titulo_secundario') border-red-500 @enderror">
                        <option value="">Seleccione una opción</option>
                        <option value="1">Fotocopia del título Secundario</option>
                        <option value="2">Certificado de constancia de título en trámite</option>
                        <option value="3">Constancia de alumno del último año del nivel secundario</option>
                        <option value="4">No entregado</option>
                    </select>
                    <div class="campo-alert">
                        @error('form.titulo_secundario')
                            {{ $message }}
                        @enderror
                    </div>
                </label>
            </div>
        </fieldset>


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
            <fieldset class="p-2" style="margin: 10px">
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
                        <input type="number" class="input" value="{{ now()->year }}" disabled>
                    </label>
                    <label class="block mb-2">
                        Índice libro matriz:
                        <input type="text" wire:model="iForm.indice_libro_matriz" class="input">
                        @error('iForm.indice_libro_matriz')
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
            <button type="button" wire:click="pasoAnterior" class="btn_blue">Volver</button>
            <button type="button" wire:click="siguientePaso" class="btn_blue">Siguiente: Confirmar</button>
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
