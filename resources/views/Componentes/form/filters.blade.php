@php
    $filtersArray = is_array($filters) ? $filters : get_object_vars($filters);
    $hasActiveFilters = !empty(array_filter($filtersArray));
@endphp

<!-- Incluir CSS y JS -->
<link rel="stylesheet" href="{{ asset('css/Admin/filters.css') }}">
<script src="{{ asset('js/filters.js') }}" defer></script>

<div class="w-100p flex-row p-2 gap-2 just-end items-center" style="position: relative;">
    <div class="flex-row items-center gap-2" style="position: relative;">
        @if ($hasActiveFilters)
            <button id="clear-filters"
        class="rounded btn_blue" style="color: white; background-color: red;"
        title="Quitar filtros"
        data-route="{{ route($url) }}">
    <i class="ti ti-x" style="font-size: 1.3em; color: white;"></i>
</button>

        @endif

        <button id="show-filters" class="rounded btn_blue" title="Filtrar búsqueda">
            <i class="ti ti-filter" style="font-size: 1.3em; margin-right: 8px;"></i>Filtrar
        </button>

        <form action="{{ route($url) }}" id="filters" class="none w-100p rounded bg-white"
    style="position: absolute; top: 110%; right: 0; z-index: 10; width: 1300px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25); border: 2px solid #e0e0e0;">
    
    @if ($dropdowns)
        <div class="grid-4 gap-3 w-100p p-2">
            @foreach ($dropdowns as $dropdown)
                {!! $dropdown !!}
            @endforeach
        </div>
    @endif

    <div class="flex just-end gap-3 w-100p p-2">
        {!! $form->select('filter_field', 'Criterio:', 'label-input-y-100', $filters, $fields, []) !!}
        {!! $form->text('filter_search_box', 'Busqueda:', 'label-input-y-100', $filters) !!}
        <div class="flex items-end just-center">
            <button class="p-2 rounded btn_blue">
                <i class="ti ti-search" style="font-size: 1.3em; margin-right: 8px;"></i>Aplicar
            </button>
        </div>
    </div>
</form>
    </div>
</div>

