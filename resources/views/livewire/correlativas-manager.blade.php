<div class="correlativas-manager">
    @if ($posiblesCorrelativas->count())
        <p><strong>Asignar correlativas para:</strong> {{ $asignatura->nombre }}</p>
        <ul class="lista-correlativas" style="padding-left: 0;">
            @foreach ($posiblesCorrelativas as $posible)
                <li style="list-style: none; margin-bottom: 5px;">
                    <label style="display: flex; align-items: center; justifi-content: center; gap: 8px;">
                        <input type="checkbox" wire:click="toggleCorrelativa({{ $posible->id }})"
                            {{ $asignatura->correlativas->contains($posible->id) ? 'checked' : '' }}
                            {{ $posible->nombre }} <small class="text-muted">({{ $posible->anio }}° año)</small>
                    </label>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">No hay asignaturas anteriores disponibles para asignar como correlativas.</p>
    @endif
</div>
