@extends('Admin.template')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">📂 Importar datos desde Excel / CSV</h3>

    {{-- Mostrar errores --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ojo!</strong> Hubo problemas con tu carga:
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.importar.preview') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        {{-- Select de tablas --}}
        <div class="mb-3">
            <label for="tabla" class="form-label">Seleccionar tabla destino</label>
            <select name="tabla" id="tabla" class="form-select" required>
                <option value="">-- Elegir tabla --</option>
                @foreach($tablas as $t)
                 @php
    $tablaNombre = array_values((array)$t)[0];
@endphp
                    <option value="{{ $tablaNombre }}">{{ $tablaNombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Input de archivo --}}
        <div class="mb-3">
            <label for="archivo" class="form-label">Archivo Excel / CSV</label>
            <input type="file" name="archivo" id="archivo" class="form-control" accept=".xlsx,.xls,.csv" required>
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search me-1"></i> Vista previa
        </button>
    </form>
</div>
@endsection
