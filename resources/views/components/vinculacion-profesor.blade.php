@php
    $asignaturasActuales = isset($profesor) ? $profesor->asignaturas->pluck('id')->toArray() : [];
    $urlVinculacion = isset($profesor) ? route('admin.profesores.vincular-asignaturas', $profesor) : null;
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

<div id="contenedorTablas" class="mt-5"></div>

@if(isset($profesor))
    <div class="text-end mt-3">
        <button id="btnVincularAsignaturas" class="btn btn-primary">
            🔗 Vincular asignaturas
        </button>
    </div>
@endif

<script>
    const carreras = @json($carreras);
    const asignaturasActuales = @json($asignaturasActuales);
    const urlVinculacion = @json($urlVinculacion);

    document.getElementById("selectorCarreras").addEventListener("change", function () {
        const seleccionadas = Array.from(this.selectedOptions).map(opt => parseInt(opt.value));
        const contenedor = document.getElementById("contenedorTablas");
        contenedor.innerHTML = "";

        seleccionadas.forEach((carreraId) => {
            const carrera = carreras.find(c => c.id === carreraId);
            if (!carrera || !carrera.asignaturas.length) return;

            const bloque = document.createElement("div");
            bloque.classList.add("card", "mb-5", "shadow-sm", "p-3");

            const agrupadasPorAnio = {};
            carrera.asignaturas.forEach(asig => {
                const anio = asig.anio ?? 'Sin año';
                if (!agrupadasPorAnio[anio]) agrupadasPorAnio[anio] = [];
                agrupadasPorAnio[anio].push(asig);
            });

            let acordeonHTML = `<h4 class="mb-4 text-primary border-bottom pb-2">📘 ${carrera.nombre}</h4>`;
            acordeonHTML += `<div class="accordion" id="acordeonCarrera${carrera.id}">`;

            Object.entries(agrupadasPorAnio).forEach(([anio, asignaturas], indexAnio) => {
                const collapseId = `collapse${carrera.id}-${indexAnio}`;
                const headingId = `heading${carrera.id}-${indexAnio}`;

                const filas = asignaturas.map(asig => {
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

                acordeonHTML += `
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="${headingId}">
                            <button class="accordion-button collapsed font-500" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#${collapseId}"
                                aria-expanded="false"
                                aria-controls="${collapseId}">
                                ${isNaN(anio) ? anio : `${anio}° año`}
                            </button>
                        </h2>
                        <div id="${collapseId}" class="accordion-collapse collapse"
                            aria-labelledby="${headingId}" data-bs-parent="#acordeonCarrera${carrera.id}">
                            <div class="accordion-body p-0">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Asignatura</th>
                                            <th>Acción</th>
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
            bloque.innerHTML = acordeonHTML;
            contenedor.appendChild(bloque);
        });
    });

    document.querySelector('form')?.addEventListener('submit', function () {
        const inputs = document.querySelectorAll('input[name^="asignaturas_seleccionadas"]');
        inputs.forEach(input => {
            const clone = input.cloneNode(true);
            clone.style.display = 'none';
            this.appendChild(clone);
        });
    });

    const btnVincular = document.getElementById("btnVincularAsignaturas");
    if (urlVinculacion && btnVincular) {
        btnVincular.addEventListener("click", function () {
            const checkboxes = document.querySelectorAll("input[type='checkbox'][name^='asignaturas_seleccionadas']");
            const seleccionadas = {};

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const carreraId = cb.name.match(/\d+/)[0];
                    if (!seleccionadas[carreraId]) seleccionadas[carreraId] = [];
                    seleccionadas[carreraId].push(parseInt(cb.value));
                }
            });

            fetch(urlVinculacion, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ asignaturas_seleccionadas: seleccionadas })
            })
            .then(response => {
                if (!response.ok) throw new Error("Error al vincular asignaturas");
                return response.json();
            })
            .then(data => {
                alert("✅ Asignaturas vinculadas correctamente");
                location.reload();
            })
            .catch(error => {
                console.error(error);
                alert("❌ Hubo un problema al vincular las asignaturas");
            });
        });
    }
</script>