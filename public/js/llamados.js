document.addEventListener('DOMContentLoaded', function () {
 // Obtiene el selector de cantidad de llamados
 const cantidadLlamados = document.getElementById('cantidad_llamados');

 // Los elementos de fecha llamados 1 y 2
 const fechaLlamado1 = document.getElementById('fecha_llamado_1');
 const fechaLlamado2 = document.getElementById('fecha_llamado_2');

 // Función para mostrar u ocultar el campo de llamado 2
 function toggleLlamados() {
  if (cantidadLlamados.value === '2') {
   fechaLlamado2.style.display = 'flex';  // Mostrar fecha llamado 2
  } else {
   fechaLlamado2.style.display = 'none';  // Ocultar fecha llamado 2
  }
 }

 // Ejecutamos la función al cargar la página para asegurarnos que el estado inicial sea correcto
 toggleLlamados();

 // Escuchar cambios en el select para cambiar entre 1 o 2 llamados
 cantidadLlamados.addEventListener('change', toggleLlamados);
});
