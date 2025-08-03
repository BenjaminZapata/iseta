@extends('Admin.template')
@section('content')
    <div class="perfil_one br">
        <div class="perfil__header">
            <h2>Mensajes</h2>
        </div>
        <div class="perfil__info flex-col gap-4">

            @foreach ($mensajes as $mensaje)
                <div class="flex-col gap-2">
                    <p>Alumno: {{$mensaje->alumno->nombreApellido()}}</p>
                    <p class="font-6">Mensaje: <span class="font-600">{{$mensaje->mensaje}}</span></p>

                    <p>Escribe una respuesta</p>
                    <form method="POST" action="{{route('admin.mensajes.update', ['mensaje' => $mensaje->id])}}"
                        class="flex-col items-start gap-2">
                        @csrf
                        @method('put')
                        <textarea name="respuesta" cols="30" rows="2">{{$mensaje->respuesta ? $mensaje->respuesta : ''}}</textarea>
                        <button class="bg-white p-2 rounded shadow">Responder</button>
                    </form>
                    <form method="POST" action="{{route('admin.mensajes.update', ['mensaje' => $mensaje->id])}}"
                        class="flex-col items-start gap-2">
                        @csrf
                        @method('delete')
                        <button class="bg-white p-2 rounded shadow">Eliminar</button>
                    </form>
                </div>
            @endforeach

        </div>

    </div>
@endsection