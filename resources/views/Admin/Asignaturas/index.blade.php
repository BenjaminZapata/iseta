@extends('Admin.template')

@section('content')

    <div>



            <a href="{{route('admin.asignaturas.create')}}"><button>Agregar carrera</button></a>

        <div>
            <form action="{{route('admin.asignaturas.index')}}">
                <p>filtrar</p>
                 <input  name="filtro" type="text">
                 <input type="submit" value="Buscar">
            </form>

            <a href="{{route('admin.asignaturas.index')}}"><button>Quitar filtro</button></a>

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
                    <th>Carrera</td>
                    <th colspan="2">Acciones</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($asignaturas as $asignatura)
                <tr>
                    <td>{{$asignatura->nombre}}</td>
                    <td>{{$asignatura->carrera->nombre}}</td>
                    <td><a href="{{route('admin.asignaturas.edit', ['asignatura' => $asignatura->id])}}"><button class="btn_edit">Editar</button></a></td>
                    <td>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="w-1/2 mx-auto p-5 pagination">
            {{ $asignaturas->appends(request()->query())->links('Componentes.pagination') }}
        </div>



@endsection
