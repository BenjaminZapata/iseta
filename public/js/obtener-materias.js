const carreraSelect = _find('#carrera_select')
const asignaturaSelect = _find('#asignatura_select')
const presidenteSelect = _find('[name="prof_presidente"]')
const vocal1Select = _find('[name="prof_vocal_1"]')
const vocal2Select = _find('[name="prof_vocal_2"]')

/* ============================================================
   CARGA INICIAL CON OLD()
   ============================================================ */
document.addEventListener("DOMContentLoaded", () => {
    const oldAsig = ASIGNATURA_OLD && ASIGNATURA_OLD !== "0" ? ASIGNATURA_OLD : 0

    if (carreraSelect.element.value != 0) {
        callback(oldAsig)   // ← SI HAY OLD DE ASIGNATURA, SE SELECCIONA
    }
})

/* ============================================================
   CAMBIO DE CARRERA
   ============================================================ */
carreraSelect.when('change', function () {
    callback(0)   // limpiar asignatura
})

/* ============================================================
   CAMBIO DE ASIGNATURA
   ============================================================ */
asignaturaSelect.when('change', function () {
    const idAsignatura = asignaturaSelect.value()
    if (!idAsignatura || idAsignatura === '0') return

    // Validar carrera
    if (!carreraSelect.value() || carreraSelect.value() === '0') {
        alert('Debe seleccionar una carrera antes de elegir la asignatura.')
        asignaturaSelect.element.value = 0
        return
    }

    // Presidente vinculado a la asignatura
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

/* ============================================================
   CARGA DE ASIGNATURAS SEGÚN CARRERA
   ============================================================ */
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

                // === OLD() ===
                if (parseInt(asigSelected) === parseInt(asignatura.id)) {
                    option.withAttrs({ selected: true })
                }
            })

            asignaturaSelect.insert()

            actualizarVocales(carreraSelect.value())
        })
        .catch(e => console.log(e))
}

/* ============================================================
   ACTUALIZA PROFESORES DISPONIBLES
   ============================================================ */
function actualizarVocales(idCarrera) {
    vocal1Select.clear()
    vocal2Select.clear()

    // Agregar "Vacío / A confirmar" si no está
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

            // Vocales: agregar vacío
            const v1Empty = vocal1Select.createChild('<option>')
                .withText('Vacío / A confirmar')
                .withAttrs({ value: '' })

            const v2Empty = vocal2Select.createChild('<option>')
                .withText('Vacío / A confirmar')
                .withAttrs({ value: '' })

            // OLD → si old() es "", que quede seleccionado ese vacío
            if (VOCAL1_OLD === "") v1Empty.withAttrs({ selected: true })
            if (VOCAL2_OLD === "") v2Empty.withAttrs({ selected: true })

            profesores.forEach(profesor => {
                const texto = `${profesor.apellido} ${profesor.nombre}`

                // Presidente: no borrar si ya está
                if (!presidenteSelect.element.querySelector(`option[value="${profesor.id}"]`)) {
                    presidenteSelect.createChild('<option>')
                        .withText(texto)
                        .withAttrs({ value: profesor.id })
                }

                // Vocales (evita repetir presidente)
                if (profesor.id == presidenteId) return

                const opt1 = vocal1Select.createChild('<option>')
                    .withText(texto)
                    .withAttrs({ value: profesor.id })

                const opt2 = vocal2Select.createChild('<option>')
                    .withText(texto)
                    .withAttrs({ value: profesor.id })

                // === OLD DE VOCALES ===
                if (parseInt(VOCAL1_OLD) === profesor.id) {
                    opt1.withAttrs({ selected: true })
                }

                if (parseInt(VOCAL2_OLD) === profesor.id) {
                    opt2.withAttrs({ selected: true })
                }
            })

            vocal1Select.insert()
            vocal2Select.insert()
        })
        .catch(e => console.log(e))
}

/* ============================================================
   VALIDACIÓN EN ENVÍO DEL FORM
   ============================================================ */
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

/* ============================================================
   BLOQUEO EN BOTÓN
   ============================================================ */
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
                alert('Debe seleccionar un presidente de mesa válido.')
                presidenteSelect.element.focus()
                return false
            }

            return true
        })
    }
})
