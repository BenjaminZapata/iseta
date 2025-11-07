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

    // ⚠️ Validar carrera antes de seguir
    if (!carreraSelect.value() || carreraSelect.value() === '0') {
        alert('Debe seleccionar una carrera antes de elegir la asignatura.')
        asignaturaSelect.element.value = 0
        return
    }

    // ✅ Evita asignar presidente vacío
    fetch(`/api/asignatura/${idAsignatura}/presidente`)
        .then(res => res.json())
        .then(data => {
            if (data.presidente_id) {
                presidenteSelect.element.value = data.presidente_id
            }
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

    // 🟢 Solo agrega la opción “Vacío / A confirmar” si no existe
    if (!presidenteSelect.element.querySelector('option[value=""]')) {
        presidenteSelect.createChild('<option>')
            .withText('Vacío / A confirmar')
            .withAttrs({ value: '' })
        presidenteSelect.insert()
    }

    fetch(`/api/carrera/${idCarrera}/profesores`)
        .then(res => res.json())
        .then(profesores => {
            const presidenteId = presidenteSelect.value()

            vocal1Select.createChild('<option>')
                .withText('Vacío / A confirmar')
                .withAttrs({ value: '' })

            vocal2Select.createChild('<option>')
                .withText('Vacío / A confirmar')
                .withAttrs({ value: '' })

            profesores.forEach(profesor => {
                const texto = `${profesor.apellido} ${profesor.nombre}`

                // 🔹 No se borra el presidente ya asignado
                if (!presidenteSelect.element.querySelector(`option[value="${profesor.id}"]`)) {
                    presidenteSelect.createChild('<option>')
                        .withText(texto)
                        .withAttrs({ value: profesor.id })
                }

                // 🔹 Vocales (vocal2 puede quedar vacío)
                if (profesor.id == presidenteId) return

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

// ⚙️ Validación mínima al enviar el formulario
document.querySelector('form').addEventListener('submit', (e) => {
    const carreraVal = carreraSelect.element.value
    const presidenteVal = presidenteSelect.element.value

    if (!carreraVal || carreraVal === '0') {
        e.preventDefault()
        alert('Debe seleccionar una carrera antes de guardar.')
        return
    }

    if (!presidenteVal || presidenteVal.trim() === '') {
        e.preventDefault()
        alert('Debe seleccionar un presidente de mesa válido (no puede quedar "Vacío / A confirmar").')
        presidenteSelect.element.focus()
        return
    }
})

// 🚫 Bloqueo absoluto de envío si el presidente está vacío (nivel botón)
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form')
    const submitBtn = form.querySelector('[type="submit"]')

    if (submitBtn) {
        submitBtn.addEventListener('click', (e) => {
            const carreraVal = carreraSelect.element.value
            const presidenteVal = presidenteSelect.element.value

            if (!carreraVal || carreraVal === '0') {
                e.preventDefault()
                alert('Debe seleccionar una carrera antes de guardar.')
                return false
            }

            if (!presidenteVal || presidenteVal.trim() === '') {
                e.preventDefault()
                alert('Debe seleccionar un presidente de mesa válido (no puede quedar "Vacío / A confirmar").')
                presidenteSelect.element.focus()
                return false
            }

            return true
        })
    }
})
