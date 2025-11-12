<div class="contenedor-tabla_botonera">

  <form class="none grid lg-block form-hh" action="{{route($url)}}">
    <div class="tabla_botonera gap-5 flex items-end">

      @if ($order)
      <div class="contenedor_ordenar">
        <span class="categoria">Ordenar</span>
        <div>
          <select class="ordenar border-none p-1 shadow" name="orden">
            @foreach ($order as $key => $value)
            <option @selected($filters['orden']==$key) value="{{$key}}">{{$value}}</option>
            @endforeach
          </select>
        </div>
      </div>
      @endif

      @if ($show)
      <div class="contenedor_filtrar">
        <span class="categoria">Mostrar</span>
        <div>
          <select class="filtrar border-none p-1 shadow" name="campo">

            @foreach ($show as $key => $value)
            <option @selected($filters['campo']==$key) value="{{$key}}">{{$value}}</option>
            @endforeach
          </select>
        </div>
      </div>
      @endif

      <div class="contenedor_filtrado">
        <input placeholder="{{$searchField['placeholder']}}" class="filtrado-busqueda border-none p-1 shadow"
          value="{{$filters['filtro']}}" name="filtro" type="text">
      </div>

      <div class="contenedor_btn-busqueda">
        <button class="btn_sky"><i class="ti ti-search"></i>Buscar</button>
      </div>
    </div>
  </form>

  <a class="none lg-block" href="{{route($url)}}"><button class="btn_red"><i class="ti ti-backspace"></i>Quitar
      filtros</button></a>
</div>