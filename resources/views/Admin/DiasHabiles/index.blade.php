@extends('Admin.template')

@section('content')
    <div class="perfil_one br">
        <div class="perfil__header">
            <h2>Días no hábiles</h2>
        </div>

        @php
            $meses = [31,29,31,30,31,30,31,31,30,31,30,31];
            $mesesStr = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        @endphp

        <div class="matricular">
            <p style="padding-bottom: 2em">Los dias no habiles o feriados se marcan en rojo.</p>
           @foreach ($meses as $key=>$mesDias)
               <div>
                <p>{{$mesesStr[$key]}}</p>
                <div class="w-100p ">
                    <ul class="flex gap-2 flex-wrap">
                        
                        @for ($i = 01; $i <= $mesDias; $i++)

                        @php
                            $dia = $i;
                            $mes = $key+1;
                            if(strlen($i) == 1) $dia='0'.$dia;
                            if(strlen($mes) == 1) $mes='0'.$mes;
                            $fecha = $dia.'-'.$mes;
                        @endphp

                            @if (in_array($fecha,$noHabiles))
                                <form method="post" action="{{route('admin.habiles.destroy',['habil' => $fecha])}}">
                                @csrf
                                @method('delete')    
                            @else    
                                <form method="post" action="{{route('admin.habiles.store')}}">
                            @endif
                                @csrf
                                <input name="fecha" type="hidden" value="{{$fecha}}">

                                <button  @class([
                                    'bg-red-200' => in_array($fecha,$noHabiles),
                                    'rounded img32 p-6 flex just-center items-center' => true
                                    ])><span>{{$i}}</span></button>
                            </form>
                        @endfor
                    </ul>
                </div>
               </div><br>
           @endforeach
        </div>
    </div>
@endsection
