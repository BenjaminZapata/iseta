<div>
    @if (true || strtotime($mesa->fecha) > time())
        <p class="py-2">
            Estos alumnos han aprobado la cursada de esta materia, luego se volverá a validar sobre correlativas y tiempos
        </p>

        {{-- Buscador --}}
        <input
            type="text"
            placeholder="Buscar alumno por DNI, nombre o apellido..."
            wire:model.live="search"
            class="border rounded px-2 py-1 mb-3 w-full"
        >

        {{-- Formulario para agregar alumno --}}
        <form method="POST" action="{{ route('admin.examenes.store') }}">
            @csrf

            <select class="rounded w-full" name="id_alumno">
                <option value="">Selecciona un alumno</option>
                @foreach ($filtered as $inscribible)
                    <option value="{{ $inscribible->id }}">
                        {{ $inscribible->apellidoNombre() }} ({{ $inscribible->dni }})
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="id_mesa" value="{{ $mesa->id }}">

            <div class="upd mt-3">
                <button class="btn_blue">
                    <i class="ti ti-upload" style="font-size: 1.3em; margin-right: 8px;"></i>
                    Cargar
                </button>
            </div>
        </form>
    @else
        Ya no se pueden agregar alumnos
    @endif
</div>
