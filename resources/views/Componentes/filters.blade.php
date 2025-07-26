<div class="contenedor-tabla_botonera">
  <form class="none grid lg-block form-hh" action="{{ route($url) }}" method="GET">
    <div class="tabla_botonera gap-5 flex items-end">

      {{-- ORDENAR --}}
      @if (!empty($order))
      <div class="contenedor_ordenar">
      <span class="categoria">Ordenar</span>
      <div>
        <select class="ordenar border-none p-1 shadow" name="orden">
        @foreach ($order as $key => $value)
      <option value="{{ $key }}" @selected(data_get($filters, 'orden') == $key)>
        {{ $value }}
      </option>
      @endforeach
        </select>
      </div>
      </div>
    @endif

      {{-- MOSTRAR POR CAMPO --}}
      @if (!empty($show))
      <div class="contenedor_filtrar">
      <span class="categoria">Mostrar</span>
      <div>
        <select class="filtrar border-none p-1 shadow" name="campo">
        @foreach ($show as $key => $value)
      <option value="{{ $key }}" @selected(data_get($filters, 'campo') == $key)>
        {{ $value }}
      </option>
      @endforeach
        </select>
      </div>
      </div>
    @endif

      {{-- TEXTO DE FILTRO --}}
      <div class="contenedor_filtrado">
        <input placeholder="{{ data_get($searchField, 'placeholder', 'Buscar...') }}"
          class="filtrado-busqueda border-none p-1 shadow" name="filtro" type="text"
          value="{{ data_get($filters, 'filtro', '') }}">
      </div>

      {{-- BOTÓN BUSCAR --}}
      <div class="contenedor_btn-busqueda">
        <button class="btn_sky"><i class="ti ti-search"></i>Buscar</button>
      </div>
    </div>
  </form>

  {{-- BOTÓN QUITAR FILTROS --}}
  <a class="none lg-block" href="{{ route($url) }}">
    <button class="btn_red"><i class="ti ti-backspace"></i>Quitar filtros</button>
  </a>
</div>