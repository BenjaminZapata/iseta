document.addEventListener('DOMContentLoaded', function () {
    const presidenteSelect = document.querySelector('[name="prof_presidente"]');
    const vocal1Select = document.querySelector('[name="prof_vocal_1"]');
    const vocal2Select = document.querySelector('[name="prof_vocal_2"]');
    const carreraId = document.querySelector('meta[name="carrera-id"]').content;

    const selectedVocal1 = parseInt(vocal1Select.dataset.selected);
    const selectedVocal2 = parseInt(vocal2Select.dataset.selected);
    const nombreVocal1 = vocal1Select.dataset.selectedNombre.trim();
    const nombreVocal2 = vocal2Select.dataset.selectedNombre.trim();

    function actualizarVocales() {
        fetch(`/api/carrera/${carreraId}/profesores`)
            .then(res => res.json())
            .then(profesores => {
                const presidenteId = parseInt(presidenteSelect.value);

                vocal1Select.innerHTML = '';
                vocal2Select.innerHTML = '';

                const crearOption = (id, nombre, seleccionado = false) => {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.text = nombre;
                    if (seleccionado) opt.selected = true;
                    return opt;
                };

                // Vacío al inicio
                vocal1Select.appendChild(crearOption(0, 'Vacío/A confirmar', selectedVocal1 === 0));
                vocal2Select.appendChild(crearOption(0, 'Vacío/A confirmar', selectedVocal2 === 0));

                // Si los vocales guardados no están en la lista, agregarlos con el nombre real
                if (selectedVocal1 !== 0 && !profesores.some(p => p.id === selectedVocal1)) {
                    vocal1Select.appendChild(crearOption(selectedVocal1, nombreVocal1, true));
                }
                if (selectedVocal2 !== 0 && !profesores.some(p => p.id === selectedVocal2)) {
                    vocal2Select.appendChild(crearOption(selectedVocal2, nombreVocal2, true));
                }

                // Agregar profesores filtrando presidente
                profesores.forEach(prof => {
                    if (prof.id === presidenteId) return;

                    const texto = prof.apellido + ' ' + prof.nombre;

                    // Vocal 1
                    if (!Array.from(vocal1Select.options).some(o => parseInt(o.value) === prof.id)) {
                        vocal1Select.appendChild(crearOption(prof.id, texto, prof.id === selectedVocal1));
                    }

                    // Vocal 2
                    if (!Array.from(vocal2Select.options).some(o => parseInt(o.value) === prof.id)) {
                        vocal2Select.appendChild(crearOption(prof.id, texto, prof.id === selectedVocal2));
                    }
                });
            })
            .catch(e => console.error('Error al cargar profesores:', e));
    }

    presidenteSelect.addEventListener('change', actualizarVocales);
    actualizarVocales();
});
