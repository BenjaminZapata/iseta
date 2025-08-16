@extends('Admin.template')

@section('content')
  <div class="contenedor-import">
    <h2>Importar datos desde Excel</h2>

    <form id="formulario" action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="campo">
      <label for="archivo">Seleccionar archivo Excel:</label>
      <input type="file" name="archivo" id="archivo" accept=".xls,.xlsx" required>
    </div>

    <div class="campo">
      <label for="tabla">Seleccionar destino:</label>
      <select name="tabla" id="tabla" required>
      <option value="">-- Seleccionar opcion --</option>
      <option value="alumnos">Alumnos</option>
      <option value="carreras">Carreras</option>
      <option value="asignaturas">Asignaturas</option>
      <option value="docentes">Docentes</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Importar</button>
    <button type="button" class="btn btn-secondary" id="btnPreview">Previsualizar</button>
    </form>

    <div id="previewContainer">
    {{-- Aquí se insertará la previsualización --}}
    </div>
  </div>

  <style>
    html,
    body {
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
    background: #f0f2f5;
    }

    .contenedor-import {
    max-width: 100vw;
    width: 100vw;
    min-height: 100vh;
    padding: 20px 40px;
    box-sizing: border-box;
    background: #fff;
    margin: 0 auto;
    }

    .campo {
    margin-bottom: 15px;
    }

    label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    }

    input[type="file"],
    select {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
    }

    button.btn {
    padding: 10px 16px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    }

    button.btn-primary {
    background-color: #007bff;
    color: white;
    }

    button.btn-primary:hover {
    background-color: #0056b3;
    }

    button.btn-secondary {
    background-color: #6c757d;
    color: white;
    margin-left: 10px;
    }

    button.btn-secondary:hover {
    background-color: #545b62;
    }

    #previewContainer {
    margin-top: 20px;
    min-height: 300px;
    max-height: 400px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    background-color: #fafafa;
    overflow-y: auto;
    overflow-x: auto;
    }

    #previewContainer table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;
    min-width: 600px;
    font-family: Arial, sans-serif;
    }

    #previewContainer th,
    #previewContainer td {
    padding: 8px 12px;
    border: 1px solid #ddd;
    white-space: nowrap;
    }

    #previewContainer thead {
    background-color: #007bff;
    color: white;
    }

    #previewContainer tbody tr:nth-child(even) {
    background-color: #f9f9f9;
    }

    #previewContainer tbody tr:hover {
    background-color: #e9f2ff;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('btnPreview').addEventListener('click', function () {
      let form = document.getElementById('formulario');
      let formData = new FormData(form);

      fetch("{{ route('importar.preview') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: formData
      })
      .then(response => response.json())
      .then(data => {
        document.getElementById('previewContainer').innerHTML = data.html || '<p>No se pudo generar la previsualización.</p>';
      })
      .catch(error => {
        console.error('Error al obtener previsualización:', error);
        document.getElementById('previewContainer').innerHTML = '<p style="color:red;">Error al generar la previsualización.</p>';
      });
    });
    });
  </script>
@endsection