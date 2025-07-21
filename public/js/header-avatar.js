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