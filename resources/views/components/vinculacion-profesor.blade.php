@php
    $asignaturasActuales = isset($profesor) ? $profesor->asignaturas->pluck('id')->toArray() : [];
@endphp

<style>
    .select-carreras {
        font-size: 1.1em;
        min-height: 180px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        background-color: #fafafa;
    }

    .select-carreras option {
        padding: 6px 10px;
    }

    .select-carreras:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        background-color: #fff;
    }
</style>

<div style="display: flex; flex-direction: column; gap: 8px;">
    <h3 class="mb-3">Seleccionar carrera/s</h3>
    <select id="selectorCarreras" multiple class="form-control select-carreras">
        @foreach ($carreras as $carrera)
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

    document.getElementById("selectorCarreras").addEventListener("change", function() {
        const seleccionadas = Array.from(this.selectedOptions).map(opt => parseInt(opt.value));
        const contenedor = document.getElementById("contenedorTablas");
        contenedor.innerHTML = "";

        seleccionadas.forEach((carreraId, index) => {
            const carrera = carreras.find(c => c.id === carreraId);
            if (!carrera || !carrera.asignaturas.length) return;

            const bloque = document.createElement("div");
            bloque.classList.add("card", "mb-5", "shadow-sm", "p-3");

            // Agrupar asignaturas por año
            const asignaturasPorAnio = {};
            carrera.asignaturas.forEach(asig => {
                const anio = asig.anio || 'Sin año';
                if (!asignaturasPorAnio[anio]) asignaturasPorAnio[anio] = [];
                asignaturasPorAnio[anio].push(asig);
            });

            // Crear acordeón por año
            let acordeonHTML = `<div class="accordion" id="accordionCarrera${carrera.id}">`;
            let anioIndex = 0;

            Object.keys(asignaturasPorAnio).sort().forEach(anio => {
                anioIndex++;
                const filas = asignaturasPorAnio[anio].map(asig => {
                    const checked = asignaturasActuales.includes(asig.id) ? 'checked' :
                        '';
                    return `
                        <tr>
                            <td class="align-middle bold">${asig.nombre}</td>
                            <td class="text-center align-middle">
                                <div style="display: flex; align-items: center; justify-content: center;">
                                    <input type="checkbox" name="asignaturas_seleccionadas[${carrera.id}][]" value="${asig.id}" ${checked}>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join("");

                acordeonHTML += `
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading${carrera.id}-${anioIndex}">
                            <button class="accordion-button collapsed font-500" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse${carrera.id}-${anioIndex}"
                                aria-expanded="false"
                                aria-controls="collapse${carrera.id}-${anioIndex}">
                                ${anio}° año
                            </button>
                        </h2>
                        <div id="collapse${carrera.id}-${anioIndex}" class="accordion-collapse collapse"
                            aria-labelledby="heading${carrera.id}-${anioIndex}"
                            data-bs-parent="#accordionCarrera${carrera.id}">
                            <div class="accordion-body p-0">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 70%;">Asignatura</th>
                                            <th class="center" style="width: 20%;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>${filas}</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
            });

            acordeonHTML += `</div>`;

            bloque.innerHTML = `
                <h4 class="mb-4 text-primary border-bottom pb-2">${carrera.nombre}</h4>
                ${acordeonHTML}
            `;

            contenedor.appendChild(bloque);
        });
    });
</script>

{{-- Componente para la selección de carreras y asignaturas al crear/editar un profesor --}}
{{-- Incluido en las vistas create.blade.php y edit.blade.php de Profesores --}}
