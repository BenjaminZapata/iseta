const carreraSelect = _find('#carrera_select')
const asignaturaSelect = _find('#asignatura_select')
const presidenteSelect = _find('[name="prof_presidente"]')
const vocal1Select = _find('[name="prof_vocal_1"]')
const vocal2Select = _find('[name="prof_vocal_2"]')

if (carreraSelect.element.value != 0) {
    const url = new URL(window.location.href)
    const parametros = new URLSearchParams(url.search)
    const valorParametro1 = parametros.get('filter_asignatura_id')

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
            actualizarVocales(carreraSelect.value())
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
            actualizarVocales(carreraSelect.value())
        })
        .catch(e => console.log(e))
}

function actualizarVocales(idCarrera) {
    vocal1Select.clear()
    vocal2Select.clear()

    fetch(`/api/carrera/${idCarrera}/profesores`)
        .then(res => res.json())
        .then(profesores => {
            const presidenteId = presidenteSelect.value()

            vocal1Select.createChild('<option>')
                .withText('Vacío/A confirmar')
                .withAttrs({ value: 0 })

            vocal2Select.createChild('<option>')
                .withText('Vacío/A confirmar')
                .withAttrs({ value: 0 })

            profesores.forEach(profesor => {
                if (profesor.id == presidenteId) return

                const texto = profesor.apellido + ' ' + profesor.nombre

                vocal1Select.createChild('<option>')
                    .withText(texto)
                    .withAttrs({ value: profesor.id })

                vocal2Select.createChild('<option>')
                    .withText(texto)
                    .withAttrs({ value: profesor.id })
            })

            vocal1Select.insert()
            vocal2Select.insert()
        })
        .catch(e => console.log(e))
}
