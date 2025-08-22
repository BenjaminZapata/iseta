function toggleUserMenu() {
 const menu = document.getElementById('user-menu');
 const arrow = document.getElementById('avatar-arrow');

 const isVisible = menu.style.display === 'block';

 menu.style.display = isVisible ? 'none' : 'block';
 arrow.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
}

// Cierra el menú si se hace clic fuera de él o del contenedor del avatar
document.addEventListener('click', function (event) {
 const menu = document.getElementById('user-menu');
 const toggleArea = document.getElementById('avatar-toggle');

 if (menu && toggleArea && !menu.contains(event.target) && !toggleArea.contains(event.target)) {
  menu.style.display = 'none';

  const arrow = document.getElementById('avatar-arrow');
  arrow.style.transform = 'rotate(0deg)';
 }
});

function toggleNotificacionesMenu() {
 const menu = document.getElementById('notificaciones-menu');
 const arrow = document.getElementById('notificaciones-arrow');

 const isVisible = menu.style.display === 'block';

 // Abrir o cerrar menú
 menu.style.display = isVisible ? 'none' : 'block';

 // Rotar flecha solo al hacer click
 arrow.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
}

// Cerrar menú si se hace click fuera
document.addEventListener('click', function (event) {
 const menu = document.getElementById('notificaciones-menu');
 const toggle = document.getElementById('notificaciones-toggle');
 const arrow = document.getElementById('notificaciones-arrow');

 if (!menu.contains(event.target) && !toggle.contains(event.target)) {
  menu.style.display = 'none';
  arrow.style.transform = 'rotate(0deg)';
 }
});


function alinearDropdowns() {
 const avatar = document.getElementById('header-avatar');
 const dropdowns = document.querySelectorAll('.header-dropdown');

 const bottom = avatar.getBoundingClientRect().bottom;

 dropdowns.forEach(dd => {
  dd.style.top = `${bottom}px`;
 });
}

window.addEventListener('resize', alinearDropdowns);
window.addEventListener('load', alinearDropdowns);

