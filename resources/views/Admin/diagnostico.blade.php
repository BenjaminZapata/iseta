@extends('Admin.template')

@section('content')

    <div class="perfil_one br">
        <div class="perfil__header">
            <h2>Irregularidades</h2>
        </div>
        <div class="perfil__info config">

            <h3>Mesas del ultimo añocon notas faltantes</h3>
            <table>
                <thead>
                    <th>materia</th>
                    <th>carrera</th>
                    <th>fecha</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($mesasSinNotas as $mesa)

                        <tr>
                            <td>{{$mesa->asignatura->nombre}}</td>
                            <td>{{$mesa->asignatura->carrera->nombre}}</td>
                            <td>{{$mesa->fecha}}</td>
                            <td><a href="{{route('Regente.mesas.edit', ['mesa' => $mesa->id])}}">revisar</a></td>
                        </tr>
                    @endforeach
                    <tr></tr>
                </tbody>
            </table>


        </div>
    </div>

@endsection