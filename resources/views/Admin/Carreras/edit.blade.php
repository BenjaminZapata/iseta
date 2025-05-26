@extends('Admin.template')

@section('content')
    <div class="edit-form-container">
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Carrera</h2>
            </div>
            <div class="perfil__info">

                <?= $form->generate(route('admin.carreras.update',['carrera'=>$carrera->id]),'put',[
                    'Información' => [
                        $form->text('nombre', 'Nombre:','label-input-y-75',$carrera),
                        $form->text('resolucion', 'Resolucion:','label-input-y-75',$carrera),
                        $form->text('anio_apertura', 'Año de apertura:','label-input-y-75',$carrera),
                        $form->text('anio_fin', 'Año de cierre:','label-input-y-75',$carrera),
                        $form->textarea('observaciones', 'Observaciones:','label-input-y-75',$carrera),
                        $form->texthidden(url()->previous())
                    ]
<<<<<<< HEAD
                    ,
=======
                    /*
>>>>>>> 4e9754a5b53c5dba6abc454a88901906016b97bb
                    'Resolución' => [
                        '<input class="campo_info3 rounded" type="file" name="resolucion_archivo">',
                        $carrera->resolucion_archivo ? '
                            <span class="font-3 font-400">' . $carrera->resolucion_archivo . '</span>
                            <div class="flex gap-4">
                                <a class="font-3 blue-700" href="' . route('admin.carreras.resolucion', ['carrera' => $carrera->id]) . '">Descargar resolución</a>
                                <a class="font-3 red-600" href="' . route('admin.carreras.resolucion.borrar', ['carrera' => $carrera->id]) . '">Eliminar esta resolución</a>
                            </div>' : ''
                    ],

                ]) ?>

            </div>
        </div>
            <div class="table">
                <div  class="perfil__header-alt">
                <a href="{{route('admin.carreras.index')}}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar asignatura</button></a>
                <a><button class="btn_blue" data-bs-toggle="modal" x-modal @click="$dispatch" data-bs-target="#asignaturasModal"><i class="ti ti-circle-plus"></i>Crear asignatura</button></a>
                <a href="/admin/cursantes/carrera/{{$carrera->id}}"><button class="btn_blue"><i class="ti ti-file-download"></i>Exportar cursadas</button></a>
            </div>
                <table class="table__body">
                    <thead>
                        <tr>
                            <th class="center">Año</th>
                            <th>Materia</th>
                            <th class="center">Carga anual/semanal</th>
                            <th class="center">Acción</th>
                            <th class="center" colspan="2">Crear</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carrera->asignaturas as $asignatura)
                            <tr>
                                <td class="center"> {{$asignatura->anio}} </td>

                                <td> {{$asignatura->nombre}} </td>

                                <td class="center"> {{$asignatura->carga_horaria}} horas</td>

                                <td style="display:flex;">
                                    <form action="{{route('admin.asignaturas.edit', ['asignatura'=>$asignatura->id])}}">
                                        <button class="btn_blue"><i class="ti ti-edit"></i>Editar</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{route('admin.mesas.create')}}">
                                        <input name="carrera" type="hidden" value="{{$carrera->id}}">
                                        <input name="asignatura" type="hidden" value="{{$asignatura->id}}">
                                        <button class="btn_blue"><i class="ti ti-circle-plus"></i>Mesa</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{route('admin.mesas.dual', ['asignatura'=>$asignatura->id])}}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Mesas</button></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @if (!$config['modo_seguro'])
            <div class="upd">
                <form method="POST" class="form-eliminar" action="{{route('admin.carreras.destroy', ['carrera' => $carrera->id])}}">
                    @csrf
                    @method('delete')
                    <button class="btn_red"><i class="ti ti-trash"></i>Eliminar carrera</button>
                </form>
            </div>
        @endif
    </div>
@endsection
