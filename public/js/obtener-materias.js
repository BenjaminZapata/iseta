const carreraSelect = _find('#carrera_select')
const asignaturaSelect = _find('#asignatura_select')
const presidenteSelect = _find('[name="prof_presidente"]')

if (carreraSelect.element.value != 0) {
    var url = new URL(window.location.href);
    var parametros = new URLSearchParams(url.search);
    var valorParametro1 = parametros.get('filter_asignatura_id');

    callback(valorParametro1)
}

carreraSelect.when('change', function () {
    callback(0)
})

asignaturaSelect.when('change', function () {
    const idAsignatura = asignaturaSelect.value()
    if (!idAsignatura || idAsignatura === '0') return

    fetch(`/api/asignatura/${idAsignatura}/presidente`)
        .then(res => res.json())
        .then(data => {
            presidenteSelect.element.value = data.presidente_id || '0'
        })
        .catch(e => console.log(e))
})

function callback(asigSelected) {
    asignaturaSelect.clear()
    if (carreraSelect.valueIs('any')) return

    fetch(`/api/a/${carreraSelect.value()}`)
        .then(asig => asig.json())
        .then(asig => {
            asignaturaSelect.createChild('<option>')
                .withText('Cualquiera')
                .withAttrs({ value: 0 })

            asig.forEach(asignatura => {
                const option = asignaturaSelect.createChild('<option>')
                    .withText(asignatura.nombre)
                    .withAttrs({ value: asignatura.id })

                if (asigSelected == asignatura.id) {
                    option.withAttrs({ selected: true })
                }
            })

            asignaturaSelect.insert()
        })
        .catch(e => console.log(e))
}
