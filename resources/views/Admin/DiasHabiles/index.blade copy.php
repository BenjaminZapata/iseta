@extends('Admin.template')

@section('content')
<div class="perfil_one br">
    <div class="perfil__header">
        <h2>Días hábiles</h2>
    </div>
    <div class="matricular">
        <form method="POST" action="{{route('admin.habiles.store')}}">
            @csrf
            <input class="rounded" name="fecha" type="date">
            <div class="upd"><button class="btn_edit">Agregar</button></div>
        </form>

        <ul>
            @foreach ($habiles as $habil)
            <li>{{$habil->fecha}}</li>

            <form method="post" action="{{route('admin.habiles.destroy', ['habil' => $habil->id])}}">
                @method('delete')
                @csrf
                <div class="upd"><button class="btn_edit">Eliminar</button></div>
            </form>
            @endforeach
        </ul>
    </div>
</div>
@endsection