<div class="w-100p flex-row p-2 gap-2 just-end">
        
        <div class="flex-col items-end"><button id="show-filters" class="p-2 rounded">Filtros</button>
        <form action="{{route($url)}}" id="filters" class="none w-100p rounded bg-white">
        @if ($dropdowns)
            <div class="grid-4 gap-3 w-100p p-2">
                @foreach ($dropdowns as $dropdown)
                    <?= $dropdown ?>        
                @endforeach
            </div>
        @endif
            <div class="flex just-end gap-3 w-100p p-2">
                <?= $form->select('filter_field','Criterio:','label-input-y-100',$filters,$fields,$options=[]) ?>
                <?= $form->text('filter_search_box', 'Busqueda:','label-input-y-100',$filters) ?>
                <div class="flex items-end just-center">
                    <button class="p-2 rounded">Aplicar</button>
                </div>
                
            </div>
    </form></div>

    
    <div class="flex items-end just-center">
        <a href="{{route($url)}}"><button class="p-2 rounded">Eliminar filtros</button></a>
    </div>
    
</div>