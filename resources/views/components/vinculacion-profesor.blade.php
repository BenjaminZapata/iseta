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
    <div class="botones-derecha">
        <button id="btnVincularAsignaturas" class="btn_blue">
            <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right:8px"></i>
            Vincular asignaturas
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

        seleccionadas.forEach(carreraId => {
            const carrera = carreras.find(c => c.id === carreraId);
            if (!carrera || !carrera.asignaturas.length) return;

            const bloque = document.createElement("div");
            bloque.classList.add("card", "mb-5", "shadow-sm", "p-3");

            // Agrupamos por pivot.anio (igual que en profesores)
            const agrupadasPorAnio = {};
            carrera.asignaturas.forEach(asig => {
                const anio = asig.pivot?.anio ?? 'Sin año';
                if (!agrupadasPorAnio[anio]) agrupadasPorAnio[anio] = [];
                agrupadasPorAnio[anio].push(asig);
            });

            let acordeonHTML = `<h4 class="mb-4 text-primary border-bottom pb-2">📘 ${carrera.nombre}</h4>`;
            acordeonHTML += `<div class="accordion" id="acordeonCarrera${carrera.id}">`;

            const añosOrdenados = Object.keys(agrupadasPorAnio).sort((a, b) => {
                if (a === 'Sin año') return 1;
                if (b === 'Sin año') return -1;
                return a - b;
            });

            añosOrdenados.forEach((anio, indexAnio) => {
                const collapseId = `collapse${carrera.id}-${indexAnio}`;
                const headingId = `heading${carrera.id}-${indexAnio}`;
                const asignaturas = agrupadasPorAnio[anio];

                // Mismo formato de años que en profesores
               const anioLabel = (anio === 'Sin año') ? 'Sin año definido' : `${parseInt(anio) + 1}° año`;


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
                }).join('');

                acordeonHTML += `
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="${headingId}">
                            <button class="accordion-button collapsed font-500" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#${collapseId}"
                                aria-expanded="false"
                                aria-controls="${collapseId}">
                                ${anioLabel}
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

    function enviarAsignaturas() {
        const checkboxes = document.querySelectorAll("input[type='checkbox'][name^='asignaturas_seleccionadas']");
        const seleccionadas = {};

        checkboxes.forEach(cb => {
            const carreraId = cb.name.match(/\d+/)[0];
            const idAsignatura = parseInt(cb.value);
            const estabaMarcada = asignaturasActuales.includes(idAsignatura);
            const estaMarcada = cb.checked;

            if (!seleccionadas[carreraId]) seleccionadas[carreraId] = [];

            if (estaMarcada && !estabaMarcada) seleccionadas[carreraId].push(idAsignatura);
        });

        const total = Object.values(seleccionadas).reduce((acc, arr) => acc + arr.length, 0);
        if (total === 0) {
            alert('❌ No hay asignaturas para asignar.');
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
        document.getElementById("btnVincularAsignaturas")?.addEventListener("click", () => enviarAsignaturas());
    }
</script>
