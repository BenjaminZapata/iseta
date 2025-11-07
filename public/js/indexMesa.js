document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filters');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        // Borrar mensajes previos
        form.querySelectorAll('.campo-error').forEach(el => el.remove());
        form.querySelectorAll('input, select').forEach(el => el.style.border = '');

        const carrera = form.querySelector('[name="filter_carrera_id"]');
        const desde = form.querySelector('[name="filter_from"]');
        const hasta = form.querySelector('[name="filter_to"]');

        let hayErrores = false;

        if (!carrera || carrera.value == 0 || carrera.value.trim() === '' || carrera.value === 'Todas') {
            mostrarError(carrera, 'Debe seleccionar una carrera.');
            hayErrores = true;
        }

        if (!desde || desde.value.trim() === '') {
            mostrarError(desde, 'Debe ingresar la fecha "Desde".');
            hayErrores = true;
        }

        if (!hasta || hasta.value.trim() === '') {
            mostrarError(hasta, 'Debe ingresar la fecha "Hasta".');
            hayErrores = true;
        }

        if (hayErrores) e.preventDefault();
    });

    function mostrarError(input, mensaje) {
        if (!input) return;

        // 🔹 Crear contenedor de error
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('campo-error');
        Object.assign(errorDiv.style, {
            color: 'red',
            fontSize: '0.85em',
            marginTop: '4px',
            textAlign: 'center', 
            width: '100%',
            fontWeight: '500',
        });
        errorDiv.textContent = mensaje;

        // 🔹 Insertar el mensaje justo debajo del input (no dentro del flex-row)
        const inputContainer = input.parentElement;
        if (inputContainer) {
            inputContainer.insertAdjacentElement('afterend', errorDiv);
        } else {
            input.insertAdjacentElement('afterend', errorDiv);
        }

        // 🔹 Resaltar borde en rojo
        input.style.border = '1px solid red';
        input.addEventListener('input', () => input.style.border = '');
    }
});


