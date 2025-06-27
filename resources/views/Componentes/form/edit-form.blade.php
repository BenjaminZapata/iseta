<form class="flex-col gap-2" method="post" action="{{$url}}">
    @csrf

    @if ($method=='put')
    @method('put')
    @endif

    @foreach ($fieldsets as $legend => $inputs)
    <fieldset class="p-2">
        <legend class="font-600 font-7">{{$legend}}</legend>
        <div class="grid-2 gap-2 p-0">
            @foreach ($inputs as $input)
            <?= $input ?>
            @endforeach
        </div>
    </fieldset>
    @endforeach
    <div class="botones-derecha">
            <div class="botones-derecha">

                <a href="{{ route('admin.alumnos.index') }}">
                    <button class="btn_cancelar" type="button">
                        <i class="ti ti-ban" style="font-size: 1.3em; margin-right: 8px;"></i> Cancelar
                    </button>
                </a>
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
        </div>
</form>
