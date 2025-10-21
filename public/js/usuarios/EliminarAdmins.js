document.addEventListener('DOMContentLoaded', () => {
    const checkTodos = document.getElementById('check-todos');
    const btnEliminar = document.getElementById('btn-eliminar-seleccionados');
    const inputIds = document.getElementById('ids-seleccionados');

    // Mensaje dinámico desde el controlador
    const mensajeNoSeleccion = window.mensajeNoSeleccion || 'No hay administradores seleccionados.';

    // Función para actualizar estado del botón
    const actualizarBoton = () => {
        const anyChecked = Array.from(document.querySelectorAll('.check-admin')).some(chk => chk.checked);
        btnEliminar.disabled = !anyChecked;
    };

    // Seleccionar/deseleccionar todos
    checkTodos.addEventListener('change', () => {
        document.querySelectorAll('.check-admin').forEach(chk => chk.checked = checkTodos.checked);
        actualizarBoton();
    });

    // Actualizar botón si se marca/desmarca un checkbox individual
    document.querySelectorAll('.check-admin').forEach(chk => {
        chk.addEventListener('change', actualizarBoton);
    });

    // Click en "Eliminar seleccionados"
    btnEliminar.addEventListener('click', () => {
        const seleccionados = Array.from(document.querySelectorAll('.check-admin'))
            .filter(chk => chk.checked);

        if(seleccionados.length === 0){
            alert(mensajeNoSeleccion);
            return;
        }

        // Construir lista de usuarios con roles
        const listaUsuarios = seleccionados.map(chk => {
            const fila = document.getElementById(`fila-${chk.value}`);
            const username = fila.querySelector('.username-text').textContent;
            const rol = fila.querySelector('.rol-text').textContent;
            return `- ${username} (${rol})`;
        }).join('\n');

        const mensaje = `¿Estás seguro de eliminar los siguientes usuarios?\n${listaUsuarios}\nESTA ACCIÓN NO SE PUEDE DESHACER.`;

        // Guardar IDs en input hidden
        inputIds.value = seleccionados.map(chk => chk.value).join(',');

        // Abrir modal de confirmación
        openGeneralModal('form-eliminar-seleccionados', mensaje);
    });

    // Inicializar el estado del botón al cargar la página
    actualizarBoton();
});
