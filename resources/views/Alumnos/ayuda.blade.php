@extends('Alumnos.layout')
@section('content')

<main id="fondo-estudiantes">
  <section class="perfil">
    <div class="perfil_header just-start gap-2">
      <h1>Perfil</h1>
    </div>


    <div class="perfil_body">
        <div class="perfil_one shadow flex-col gap-5">
            <h2>Preguntas y dudas frecuentes</h2>

            <div class="flex-col gap-5">
                <div>
                    <p class="font-6 font-600">Pregunta 1</p>
                    <p class="font-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident illo quisquam eveniet deserunt, sunt iure expedita esse sapiente, qui aperiam at, minima consequatur quo autem fugit enim ea iste quasi.</p>
                </div>
                <div>
                    <p class="font-6 font-600">Pregunta 2</p>
                    <p class="font-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident illo quisquam eveniet deserunt, sunt iure expedita esse sapiente, qui aperiam at, minima consequatur quo autem fugit enim ea iste quasi.</p>
                </div>
                <div>
                    <p class="font-6 font-600">Pregunta 3</p>
                    <p class="font-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident illo quisquam eveniet deserunt, sunt iure expedita esse sapiente, qui aperiam at, minima consequatur quo autem fugit enim ea iste quasi.</p>
                </div>
            </div>

        </div>
        <div class="perfil_one shadow flex-col gap-2">
            <h2>Envia un mensaje</h2>
            <p  class="font-5">Puedes enviar un mensaje contandonos en detalle tu problema y trataremos de solucionarlo lo antes posible</p>
            <form class="font-5" action="{{route('alumno.ayuda.post')}}" method="POST">
                @csrf
                <div class="flex-col items-start gap-2">
                    <label for="">¿Cual es tu problema?</label>
                    <textarea name="mensaje" id="" cols="30" rows="10"></textarea>
                    <button class="rounded p-2 bg-white shadow">Enviar</button>
                </div>
            </form>
            <div>
                <h3>Mis mensajes</h3>
                <div class="flex-col gap-3">
                    @foreach ($mensajes as $mensaje)
                    <div class="flex-col">
                        <p class="font-6 font-600">{{$mensaje->mensaje}}</p>
                        <p class="font-5">{{$mensaje->respuesta? $mensaje->respuesta:'sin respuesta'}}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>





</main>
@endsection
