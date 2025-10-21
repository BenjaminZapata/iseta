@extends('Admin.template')

@section('content')

<link rel="stylesheet" href="{{ asset('css/Admin/calendario.css') }}">

@php
use Carbon\Carbon;

$mesesStr = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

$mesActual = request()->get('mes', now()->month);
$mesActual = max(1, min(12, (int)$mesActual)); // Forzamos de 1 a 12

$carbon = Carbon::createFromDate(now()->year, $mesActual, 1);
$diasEnMes = $carbon->daysInMonth;
$primerDiaSemana = $carbon->copy()->startOfMonth()->dayOfWeekIso; // 1 (lunes) a 7 (domingo)
@endphp

<div class="perfil_one br">
    @include('components.header-avatar', ['tituloSeccion' => 'DIAS NO HÁBILES'])

    <div class="dias-hab">
        <div class="mes-selector">
            @foreach ($mesesStr as $index => $mesNombre)
            <a
                href="{{ route(Route::currentRouteName(), ['mes' => $index + 1]) }}"
                @class([ 'mes-pill' , 'active'=> ($mesActual === $index + 1)
                ])
                >{{ substr($mesNombre, 0, 3) }}</a>
            @endforeach
        </div>

        <div class="calendario-seccion">
            <div class="calendario-encabezado">
                <h3 class="calendario-mes-titulo">{{ $mesesStr[$mesActual - 1] }}</h3>
            </div>

            <div class="calendario-grid">
                <div>Lun</div>
                <div>Mar</div>
                <div>Mié</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sáb</div>
                <div>Dom</div>

                {{-- Espacios en blanco antes del primer día --}}
                @for ($e = 1; $e < $primerDiaSemana; $e++)
                    <div class="calendario-dia empty">
            </div>
            @endfor

            {{-- Días del mes --}}
            @for ($d = 1; $d <= $diasEnMes; $d++)
                @php
                $date=Carbon::createFromDate(now()->year, $mesActual, $d);
                $dia = str_pad($d, 2, '0', STR_PAD_LEFT);
                $mes = str_pad($mesActual, 2, '0', STR_PAD_LEFT);
                $fecha = $dia . '-' . $mes;
                $esNoHabil = in_array($fecha, $noHabiles);
                $diaSemana = $date->dayOfWeekIso;
                $esFinDeSemana = in_array($diaSemana, [6,7]);
                @endphp

                <div>
                    @if ($esFinDeSemana)
                    <button class="dia-btn disabled" disabled>{{ $d }}</button>
                    @else
                    <form method="post" action="{{ $esNoHabil ? route('admin.habiles.destroy', ['habil' => $fecha]) : route('admin.habiles.store') }}">
                        @csrf
                        @if ($esNoHabil)
                        @method('delete')
                        @endif
                        <input type="hidden" name="fecha" value="{{ $fecha }}">
                        <button
                            type="submit"
                            @class([ 'dia-btn' , 'no-habil'=> $esNoHabil,
                            'habil' => !$esNoHabil,
                            ])
                            >{{ $d }}</button>
                    </form>
                    @endif
                </div>
                @endfor
        </div>
    </div>

    <div id="help-button" onclick="toggleHelp()">
        ?
    </div>

    <div id="help-text" class="help-box hidden">
        <p>
            Los días no hábiles o feriados se marcan en rojo y no se contarán como horas hábiles previas a una mesa. Además, no se permitirá crear mesas en un día no hábil.
        </p>
        <p>
            Los fines de semana aparecen deshabilitados y no es necesario marcarlos.
        </p>
    </div>

    <div class="botones-derecha">
        <x-btn-cancelar />
    </div>
</div>
</div>

<script>
    function toggleHelp() {
        const helpBox = document.getElementById('help-text');
        helpBox.classList.toggle('hidden');
    }
</script>
@endsection