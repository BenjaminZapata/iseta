@extends('Admin.template')

@section('content')

    <div>


        <a href="{{route('Regente.examenes.create')}}"><button>Inscribir alumno</button></a>

        <div>
            <form action="{{route('Regente.examenes.index')}}">
                <p>filtrar</p>
                <select name="campo">
                    <option @selected($filtros['campo'] == 'principales') value="principales">Principales</option>
                    <option @selected($filtros['campo'] == 'materia') value="materia">Materia</option>
                    <option @selected($filtros['campo'] == 'dni') value="dni">Dni</option>
                    <option @selected($filtros['campo'] == 'nombre') value="nombre">Nombre</option>
                    <option @selected($filtros['campo'] == 'apellido') value="apellido">Apellido</option>
                    <option @selected($filtros['campo'] == 'email') value="email">Email</option>
                    <option @selected($filtros['campo'] == 'telefonos') value="telefonos">Telefonos</option>
                    <option @selected($filtros['campo'] == 'ciudad') value="ciudad">Ciudad</option>
                </select>
                <input value="{{$filtros['filtro']}}" name="filtro" type="text">
                <input type="submit" value="Buscar">
            </form>

            <a href="{{route('Regente.examenes.index')}}"><button>Quitar filtro</button></a>

        </div>
    </div>

    {{-- @foreach ($alumnos->pagr as )

    @endforeach
    <li class="page-item{{ $page == $alumnos->currentPage() ? ' active' : '' }}">
        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
    </li> --}}

    <div class="table">
        <table class="table__body">
            <thead>
                <tr>
                    <th>Nombre</td>
                    <th>Apellido</td>
                    <th>Materia</td>
                    <th>Nota</td>
                    <th colspan="2">Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($examenes as $examen)
                    <tr>
                        <td>{{$examen->nombre}}</td>
                        <td>{{$examen->apellido}}</td>
                        <td>{{$examen->materia}}</td>
                        <td>{{$examen->nota}}</td>
                        <td><a href="{{route('Regente.examenes.edit', ['examen' => $examen->id])}}"><button
                                    class="btn_edit">Editar</button></a></td>
                        <td>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="w-1/2 mx-auto p-5 pagination">
        {{ $examenes->appends(request()->query())->links('Componentes.pagination') }}
    </div>


@endsection