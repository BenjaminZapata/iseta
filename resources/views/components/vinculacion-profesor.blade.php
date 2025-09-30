@php
    $asignaturasActuales = isset($profesor) ? $profesor->asignaturas->pluck('id')->toArray() : [];
@endphp

<div class="label-input-y-75 mt-5">
    <h3 class="mb-3">🎓 Seleccionar carrera/s</h3>
    <select id="selectorCarreras" multiple class="form-control">
        @foreach($carreras as $carrera)
            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
        @endforeach
    </select>
    <small class="form-text text-muted">Usá Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples carreras.</small>
</div>

<div id="contenedorTablas" class="mt-5">
    <!-- Tablas de asignaturas aparecerán aquí -->
</div>

<script>
    const carreras = @json($carreras);
    const asignaturasActuales = @json($asignaturasActuales);

    document.getElementById("selectorCarreras").addEventListener("change", function () {
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
                    <tr>
                        <td class="align-middle">${asig.nombre}</td>
                        <td class="text-center align-middle">
                            <input type="checkbox" name="asignaturas_seleccionadas[${carrera.id}][]" value="${asig.id}" ${checked}>
                        </td>
                    </tr>
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
