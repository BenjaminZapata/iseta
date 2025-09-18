<div x-show="$wire.entangle('show')" x-cloak>
    <form wire:submit.prevent="guardar" class="p-3 border rounded bg-gray-50">
        <h3 class="font-bold mb-2">Datos de inscripción</h3>

        <label class="block mb-2">
            Año de inscripción:
            <input type="number" wire:model="anio_inscripcion" class="input">
            @error('anio_inscripcion') <span class="text-red-500">{{ $message }}</span> @enderror
        </label>

        <label class="block mb-2">
            Índice libro matriz:
            <input type="text" wire:model="indice_libro_matriz" class="input">
            @error('indice_libro_matriz') <span class="text-red-500">{{ $message }}</span> @enderror
        </label>

        <label class="block mb-2">
            Año de finalización:
            <input type="number" wire:model="anio_finalizacion" class="input">
            @error('anio_finalizacion') <span class="text-red-500">{{ $message }}</span> @enderror
        </label>

        <label class="block mb-2">
            Estado:
            <select wire:model="estado" class="input">
                <option value="">Seleccione...</option>
                <option value="0">Activo</option>
                <option value="1">Suspendido</option>
                <option value="2">Finalizado</option>
            </select>
            @error('estado') <span class="text-red-500">{{ $message }}</span> @enderror
        </label>

        <div class="mt-3 flex gap-2">
            <button type="button" wire:click="$set('show', false)" class="btn-secondary">Cancelar</button>
            <button type="submit" class="btn-primary">Guardar inscripción</button>
        </div>
    </form>
</div>
