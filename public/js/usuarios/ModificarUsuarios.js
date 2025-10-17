document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.btn-modificar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const fila = e.target.closest('tr');
            editarFila(fila);
        });
    });

    document.querySelectorAll('.btn-guardar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const fila = e.target.closest('tr');
            guardarFila(fila);
        });
    });

    document.querySelectorAll('.btn-cancelar').forEach(btn => {
        btn.addEventListener('click', () => location.reload());
    });

});

function editarFila(fila) {
    const id = fila.dataset.id;
    const username = fila.dataset.username;
    const rol = fila.dataset.rol;
    const email = fila.dataset.email;

    fila.querySelector('.username-text').innerHTML = `<input type="text" id="input-username-${id}" value="${username}" class="w-full">`;
    fila.querySelector('.email-text').innerHTML = `<input type="email" id="input-email-${id}" value="${email}" class="w-full">`;
    fila.querySelector('.password-text').innerHTML = `<input type="password" id="input-password-${id}" placeholder="Ingrese nueva contraseña si quiere cambiarla" class="w-full">`;

    const rolOptions = {0:'Regente',1:'Preceptor',2:'Secretario'};
    let selectHTML = `<select id="input-rol-${id}" class="w-full">`;
    for(const key in rolOptions){
        selectHTML += `<option value="${key}" ${key == rol ? 'selected' : ''}>${rolOptions[key]}</option>`;
    }
    selectHTML += `</select>`;
    fila.querySelector('.rol-text').innerHTML = selectHTML;

    fila.querySelector('.btn-modificar').style.display = 'none';
    fila.querySelector('.btn-guardar').style.display = 'inline-block';
    fila.querySelector('.btn-cancelar').style.display = 'inline-block';
}

function guardarFila(fila) {
    const id = fila.dataset.id;
    const usernameInput = document.getElementById(`input-username-${id}`);
    const emailInput = document.getElementById(`input-email-${id}`);
    const rolInput = document.getElementById(`input-rol-${id}`);
    const passwordInput = document.getElementById(`input-password-${id}`);

    // Limpiar errores previos
    fila.querySelectorAll('.error-text').forEach(e => e.remove());

    fetch(`/admin/admins/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            username: usernameInput.value,
            email: emailInput.value,
            rol: rolInput.value,
            password: passwordInput.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else if (data.errors) {
            mostrarErrores(data.errors, usernameInput, emailInput, rolInput, passwordInput);
        }
    })
    .catch(err => console.error(err));
}

function mostrarErrores(errors, usernameInput, emailInput, rolInput, passwordInput){
    if(errors.username){
        const span = document.createElement('div');
        span.classList.add('error-text');
        span.style.color = 'red';
        span.textContent = errors.username[0];
        usernameInput.parentNode.appendChild(span);
        usernameInput.focus();
    }
    if(errors.email){
        const span = document.createElement('div');
        span.classList.add('error-text');
        span.style.color = 'red';
        span.textContent = errors.email[0];
        emailInput.parentNode.appendChild(span);
    }
    if(errors.rol){
        const span = document.createElement('div');
        span.classList.add('error-text');
        span.style.color = 'red';
        span.textContent = errors.rol[0];
        rolInput.parentNode.appendChild(span);
    }
    if(errors.password){
        const span = document.createElement('div');
        span.classList.add('error-text');
        span.style.color = 'red';
        span.textContent = errors.password[0];
        passwordInput.parentNode.appendChild(span);
    }
}
