@php
$asignaturasActuales = isset($profesor) ? $profesor->asignaturas->pluck('id')->toArray() : [];
@endphp

<link rel="stylesheet" href="{{ asset('css/Admin/vinculacion.css') }}">

<div class="vinculacion-bloque">
    <h3>🎓 Seleccionar carrera/s</h3>
    <select id="selectorCarreras" multiple class="vinculacion-select">
        @foreach($carreras as $carrera)
        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
        @endforeach
    </select>

    <div id="contenedorTablas"></div>

    <div class="vinculacion-seleccionadas">
        <h4>Asignaturas seleccionadas</h4>
        <ul id="listaSeleccionadas"></ul>
    </div>
</div>


<script>
    const carreras = @json($carreras);
    const asignaturasActuales = @json($asignaturasActuales);

    document.getElementById("selectorCarreras").addEventListener("change", function() {
        const seleccionadas = Array.from(this.selectedOptions).map(opt => parseInt(opt.value));
        const contenedor = document.getElementById("contenedorTablas");
        contenedor.innerHTML = "";

        seleccionadas.forEach((carreraId, index) => {
            const carrera = carreras.find(c => c.id === carreraId);
            if (!carrera || !carrera.asignaturas.length) return;

            const bloque = document.createElement("div");
            bloque.classList.add("card", "mb-5", "shadow-sm", "p-3");

            const filas = carrera.asignaturas.map(asig => {
                const checked = asignaturasActuales.includes(asig.id) ? 'checked' : '';
                return `
    <label class="vinculacion-item">
        <input type="checkbox" name="asignaturas_seleccionadas[${carrera.id}][]" value="${asig.id}" ${checked}>
        <span>${asig.nombre}</span>
    </label>
`;

            }).join("");

            bloque.innerHTML = `
                <h4 class="mb-4 text-primary border-bottom pb-2">📘 ${carrera.nombre}</h4>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 70%;">Asignatura</th>
                            <th style="width: 20%;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>${filas}</tbody>
                </table>
            `;

            contenedor.appendChild(bloque);
        });
    });
</script>
{{-- Componente para la selección de carreras y asignaturas al crear/editar un profesor --}}
{{-- Incluido en las vistas create.blade.php y edit.blade.php de Profesores --}}