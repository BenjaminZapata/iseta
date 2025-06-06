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

    <div class="upd"><button class="btn_blue"><i class="ti ti-refresh"></i>Actualizar</button></div>
   </form>