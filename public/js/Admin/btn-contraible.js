// global-buttons.js (opcional)
document.addEventListener('DOMContentLoaded', () => {
 const expandableBtns = document.querySelectorAll('.btn_icon-expandible');

 expandableBtns.forEach(btn => {
  btn.setAttribute('title', btn.textContent.trim()); // Accesibilidad mínima
 });
});


