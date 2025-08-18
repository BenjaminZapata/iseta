
<div class="dropdown! dropdown-bottom! dropdown-end!">
  <div tabindex="0" role="button" class="btn border-transparent text-2xl m-1"><i class="ti ti-bell"></i></div>
    @forelse ($notificaciones as $notificacion)
        <div tabindex="0" class="dropdown-content bg-white card! card-sm! z-1 w-64 ">
            <div class="card-body!  text-[#333333]!">
                <a href="#" wire:click.prevent="marcarComoLeida('{{ $notificacion->id }}')">
                    <h2 class="card-title">{{ $notificacion->data['title'] }}</h2>
                    <br>
                    <p class="">
                        {{ $notificacion->data['message'] }}
                    </p>
                </a>
            </div>
        </div>
    @empty
        <div tabindex="0" class="dropdown-content card card-sm bg-base-100 z-1 w-64 shadow-md">
            <div class="card-body text-gray-500">No hay notificaciones</div>
        </div>
    @endforelse
</div>
