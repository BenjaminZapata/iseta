<form class="flex-col gap-2" method="post" action="{{ $url }}">
    @csrf

    @if ($method == 'put')
    @method('put')
    @endif

    @foreach ($fieldsets as $legend => $inputs)
    <fieldset class="p-2" style="margin: 10px 30px 10px 30px; border: 1px solid #000000ff; border-radius: 8px;">
        <legend class="font-600 font-7">{{ $legend }}</legend>
        <div class="grid-2 gap-2 p-0">
            @foreach ($inputs as $input)
            <?= $input ?>
            @endforeach
        </div>
    </fieldset>
    @endforeach
    <div class="botones-derecha">
        <x-botones-alumno />
        <x-btn-cancelar />
        <button type="submit" class="btn_blue">
            @if ($method == 'put')
            <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
            Actualizar
            @else
            <i class="ti ti-user-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
            Guardar
            @endif
        </button>
    </div>
</form>

<script>
    function toggleExportar() {
        const opciones = document.getElementById('exportar-opciones');
        opciones.style.display = opciones.style.display === 'none' ? 'block' : 'none';
    }

    // Opcional: cerrar si clickean fuera
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('exportar-opciones');
        const button = event.target.closest('.dropdown');

        if (!button) {
            dropdown.style.display = 'none';
        }
    });
</script>