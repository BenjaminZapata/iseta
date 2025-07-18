const button = document.querySelector('#show-filters');
const filters = document.querySelector('#filters');
const clearBtn = document.getElementById("clear-filters");
if (clearBtn) {
    clearBtn.addEventListener("click", () => {
        window.location.href = clearBtn.dataset.route;
    });
}

button.onclick = function () {
    filters.classList.toggle('none');
}