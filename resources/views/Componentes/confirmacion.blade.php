<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
    }

    .modal-box {
        background-color: #f9fafb;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        text-align: center;
        max-width: 400px;
        width: 90%;
    }

    .modal-buttons {
        display: flex;
        justify-content: space-around;
        margin-top: 1rem;
    }

    .btn-cancel {
        background-color: #e5e7eb;
        color: #111827;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        cursor: pointer;
    }

    .btn-confirm {
        background-color: #dc2626;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        cursor: pointer;
    }
</style>

<div id="general-confirm-modal" class="modal-overlay">
    <div class="modal-box">
        <p id="modal-message" class="mb-4">¿Estás seguro de que querés realizar esta acción?</p>
        <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeGeneralModal()">Cancelar</button>
            <button class="btn-confirm" onclick="submitGeneralForm()">Confirmar</button>
        </div>
    </div>
</div>

<script>
    let generalForm = null;
    let toggleCheckbox = null;
    let estadoTexto = null;

    // 🔹 Abrir modal con mensaje
    function openGeneralModal(formId, message = '¿Estás seguro de que querés realizar esta acción?') {
        generalForm = document.getElementById(formId);
        document.getElementById('modal-message').innerText = message;
        document.getElementById('general-confirm-modal').style.display = 'flex';
    }

    // 🔹 Acción al usar el switch de carrera
    function onToggleCarrera(checkbox, carreraId) {
        toggleCheckbox = checkbox;
        estadoTexto = document.getElementById("estado-texto");

        if (checkbox.checked) {
            openGeneralModal(`form-reactivar-${carreraId}`, "¿Querés reactivar esta carrera?");
        } else {
            openGeneralModal(`form-desactivar-${carreraId}`, "¿Seguro que querés desactivar esta carrera?");
        }
    }

    // 🔹 Cancelar → volver al estado original
    function closeGeneralModal() {
        document.getElementById('general-confirm-modal').style.display = 'none';

        // Si era un toggle, lo revierte
        if (toggleCheckbox) {
            toggleCheckbox.checked = !toggleCheckbox.checked;
        }

        generalForm = null;
        toggleCheckbox = null;
        estadoTexto = null;
    }

    // 🔹 Confirmar → enviar form y actualizar texto de estado si aplica
    function submitGeneralForm() {
        if (generalForm) {
            generalForm.submit();

            if (toggleCheckbox && estadoTexto) {
                if (toggleCheckbox.checked) {
                    estadoTexto.textContent = "Activa";
                    estadoTexto.style.color = "#4cd964";
                } else {
                    estadoTexto.textContent = "Inactiva";
                    estadoTexto.style.color = "#dc2626";
                }
            }
        }
    }
</script>