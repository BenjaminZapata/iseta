@extends('Admin.template')

@section('content')



    {{-- TABLA --}}
    <div class="table">
        <div class="perfil__header-alt">
            <a href="{{route('admin.carreras.create')}}">
                <button class="btn_blue">
                    <i class="ti ti-circle-plus"></i>Agregar carrera
                </button>
            </a>
            {{-- FILTROS --}}
            <?= $filtergen->generate('admin.carreras.index', $filters, [
        'dropdowns' => [
            $form->select('filter_vigente', 'Condición: ', 'label-input-y', $filters, ['Todas', 'No Vigentes', 'Vigentes'])
        ],
        'fields' => ['nombre' => 'Nombre', 'asignatura' => 'Asignatura']
    ]) ?>
        </div>
        <table class="table__body">
            <thead>
                <tr>
                    <th>Carrera</td>
                        {{--
                    <th class="center">Resolución</th>--}}
                    <th class="center">Apertura</th>
                    <th class="center">Estado</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carreras as $carrera)
                    <tr>
                        <td>{{$carrera->nombre}}</td>
                        {{--<td class="center">{{$carrera->resolucion}}</td>--}}
                        <td class="center">{{$carrera->anio_apertura}}</td>
                        <td class="center">{{$carrera->vigente == 1 ? "Vigente" : $carrera->anio_fin}}</td>
                        <td><a href="{{route('admin.carreras.edit', ['carrera' => $carrera])}}"><button class="btn_blue"><i
                                        class="ti ti-file-info"></i>Detalles</button></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="w-1/2 mx-auto p-5 pagination">
        {{ $carreras->appends(request()->query())->links('Componentes.pagination') }}
    </div>
@endsection