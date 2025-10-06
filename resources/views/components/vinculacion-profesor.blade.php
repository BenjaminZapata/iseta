@php
$asignaturasActuales = isset($profesor) ? $profesor->asignaturas->pluck('id')->toArray() : [];
$urlVinculacion = isset($profesor) ? route('admin.profesores.vincular-asignaturas', $profesor) : null;
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

<div id="contenedorTablas" class="mt-5"></div>

@if(isset($profesor))
    <div class="text-end mt-3 d-flex gap-2 justify-content-end">
        <button id="btnVincularAsignaturas" class="btn btn-primary">
            🔗 Vincular asignaturas
        </button>
        <button id="btnDesvincularAsignaturas" class="btn btn-danger">
            ❌ Desvincular asignaturas
        </button>
    </div>
@endif

<script>
    const carreras = @json($carreras);
    const asignaturasActuales = @json($asignaturasActuales);
    const urlVinculacion = @json($urlVinculacion);

    document.getElementById("selectorCarreras").addEventListener("change", function() {
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

            let acordeonHTML =
                `<h4 class="mb-4 text-primary border-bottom pb-2">📘 ${carrera.nombre}</h4>`;
            acordeonHTML += `<div class="accordion" id="acordeonCarrera${carrera.id}">`;

            Object.entries(agrupadasPorAnio).forEach(([anio, asignaturas], indexAnio) => {
                const collapseId = `collapse${carrera.id}-${indexAnio}`;
                const headingId = `heading${carrera.id}-${indexAnio}`;

                const filas = asignaturas.map(asig => {
                    const checked = asignaturasActuales.includes(asig.id) ? 'checked' :
                        '';
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

    function enviarAsignaturas(tipo) {
        const checkboxes = document.querySelectorAll("input[type='checkbox'][name^='asignaturas_seleccionadas']");
        const seleccionadas = {};

        checkboxes.forEach(cb => {
            const carreraId = cb.name.match(/\d+/)[0];
            const idAsignatura = parseInt(cb.value);
            const estabaMarcada = asignaturasActuales.includes(idAsignatura);
            const estaMarcada = cb.checked;

            if (!seleccionadas[carreraId]) {
                seleccionadas[carreraId] = { vincular: [], desvincular: [] };
            }

            if (tipo === 'vincular' && estaMarcada && !estabaMarcada) {
                seleccionadas[carreraId].vincular.push(idAsignatura);
            }

            if (tipo === 'desvincular' && !estaMarcada && estabaMarcada) {
                seleccionadas[carreraId].desvincular.push(idAsignatura);
            }
        });

        const totalCambios = Object.values(seleccionadas).reduce((acc, obj) =>
            acc + obj.vincular.length + obj.desvincular.length, 0);

        if (totalCambios === 0) {
            const mensaje = tipo === 'vincular'
                ? '❌ No hay asignaturas para asignar.'
                : '❌ No hay asignaturas para quitar.';
            alert(mensaje);
            return;
        }

        const form = document.createElement("form");
        form.method = "POST";
        form.action = urlVinculacion;

        const csrf = document.createElement("input");
        csrf.type = "hidden";
        csrf.name = "_token";
        csrf.value = "{{ csrf_token() }}";
        form.appendChild(csrf);

        const payload = document.createElement("input");
        payload.type = "hidden";
        payload.name = "asignaturas_payload";
        payload.value = JSON.stringify(seleccionadas);
        form.appendChild(payload);

        document.body.appendChild(form);
        form.submit();
    }

    if (urlVinculacion) {
        document.getElementById("btnVincularAsignaturas")?.addEventListener("click", () => enviarAsignaturas('vincular'));
        document.getElementById("btnDesvincularAsignaturas")?.addEventListener("click", () => enviarAsignaturas('desvincular'));
    }
</script>