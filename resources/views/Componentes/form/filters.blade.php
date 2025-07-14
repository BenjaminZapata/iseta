<div class="w-100p flex-row p-2 gap-2 just-end" style="position: relative;">
    <div class="flex-col items-end mb-2" style="position: relative;">
        <button id="show-filters" class="rounded btn_blue">
            <i class="ti ti-filter" style="font-size: 1.3em; margin-right: 8px;"></i>Filtros
        </button>
        <form action="{{route($url)}}" id="filters" class="none w-100p rounded bg-white"
            style="top: 110%; right: 0; z-index: 10; min-width: 750px;">
            @if ($dropdowns)
            <div class="grid-4 gap-3 w-100p p-2">
                @foreach ($dropdowns as $dropdown)
                <?= $dropdown ?>
                @endforeach
            </div>
            @endif
            <div class="flex just-end gap-3 w-100p p-2">
                <?= $form->select('filter_field', 'Criterio:', 'label-input-y-100', $filters, $fields, $options = []) ?>
                <?= $form->text('filter_search_box', 'Busqueda:', 'label-input-y-100', $filters) ?>
                <div class="flex items-end just-center">
                    <button class="p-2 rounded btn_blue">
                        <i class="ti ti-search" style="font-size: 1.3em; margin-right: 8px;"></i>Aplicar
                    </button>
                </div>

            </div>
        </form>
    </div>
    <div class="flex items-end just-center self-start">
        <a href="{{route($url)}}">
            <button class="rounded btn_blue" style="padding: 12px 32px;">
                <i class="ti ti-filter-off" style="font-size: 1.3em; margin-right: 10px;"></i>Eliminar filtros
            </button>
        </a>
    </div>
</div>