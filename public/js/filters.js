const button = document.querySelector('#show-filters');
const filters = document.querySelector('#filters');
const clearBtn = document.getElementById("clear-filters");

if (clearBtn) {
    clearBtn.addEventListener("click", () => {
        window.location.href = clearBtn.dataset.route;
    });
}

button.onclick = function (e) {
    e.stopPropagation(); // evitar que el click se propague y cierre inmediatamente
    filters.classList.toggle('none');
};

// Cerrar dropdown al hacer click fuera
document.addEventListener('click', (e) => {
    if (!filters.classList.contains('none') && !filters.contains(e.target) && e.target !== button) {
        filters.classList.add('none');
    }
});
