<form class="perfil__info" method="post" action="{{ $url }}" enctype="multipart/form-data">
    @csrf

    @if ($method == 'put')
        @method('put')
    @endif

    @foreach ($fieldsets as $legend => $inputs)
        <fieldset class="p-2" style="margin: 10px;">
            <legend class="font-600 font-7">{{ $legend }}</legend>

            @if ($legend == 'Vinculación')
                <div style="display: flex; flex-direction: column; gap: 10px; margin: 20px 0;">
                    @foreach ($inputs as $input)
                        <?= $input ?>
                    @endforeach
                </div>
            @else
                <div class="grid-2 gap-2 p-0">
                    @foreach ($inputs as $input)
                        <?= $input ?>
                    @endforeach
                </div>
            @endif
        </fieldset>
    @endforeach

    <div class="botones-derecha">
        <x-botones-alumno />
        <x-btn-cancelar />
        <button type="submit" class="btn_blue">
            @if ($method == 'put')
                <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
                Actualizar
            @elseif ($method == 'post')
                <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                Guardar
            @endif
        </button>
    </div>
</form>
