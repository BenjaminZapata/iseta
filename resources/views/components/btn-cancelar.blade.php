<button class="btn_cancelar" type="button" onclick="volverAtras()" style="display: flex; align-items: center;">
    <i class="ti ti-ban" style="font-size: 1.3em; margin-right: 8px;"></i> Cancelar
</button>

<script>
    function volverAtras() {
        if (document.referrer) {
            // Si hay una página anterior en el historial, volvemos
            window.history.back();
        } else {
            // Si no hay referrer, redirigimos a una ruta predeterminada
            window.location.href = "{{ route('admin.alumnos.index') }}"; // <-- cambia esto a la ruta que quieras
        }
    }
</script>
