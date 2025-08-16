@extends('Admin.template')

@section('content')

 <style>
  table.preview-table {
   width: 100%;
   border-collapse: collapse;
   font-family: Arial, sans-serif;
   margin-top: 10px;
  }

  table.preview-table thead {
   background-color: #2a9df4;
   color: white;
  }

  table.preview-table th,
  table.preview-table td {
   padding: 10px 12px;
   border: 1px solid #ddd;
   text-align: left;
   max-width: 150px;
   word-wrap: break-word;
  }

  table.preview-table tbody tr:nth-child(even) {
   background-color: #f9f9f9;
  }

  table.preview-table tbody tr:hover {
   background-color: #e6f2ff;
  }

  #previewContainer {
   max-height: 350px;
   /* límite de altura */
   overflow-y: auto;
   /* scroll vertical si excede altura */
   overflow-x: auto;
   /* scroll horizontal si tabla ancha */
   border: 1px solid #ccc;
   padding: 10px;
   border-radius: 6px;
   background-color: #fafafa;
  }

  #previewContainer table {
   width: 100%;
   border-collapse: collapse;
   table-layout: auto;
   /* para que no quede comprimida */
   min-width: 600px;
   /* si querés forzar mínimo ancho para que no se achique demasiado */
  }

  #previewContainer th,
  #previewContainer td {
   padding: 8px 12px;
   border: 1px solid #ddd;
   white-space: nowrap;
   /* evitar quiebre de texto */
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

  th select {
   margin-top: 6px;
   width: 100%;
   padding: 4px 6px;
   border-radius: 4px;
   border: 1px solid #ccc;
   font-size: 0.9em;
  }
 </style>

 <h2>Vista previa del archivo Excel</h2>

 {{-- Debug para ver qué datos llegan --}}
 <p><strong>DEBUG - HEADINGS:</strong></p>
 <pre>{{ print_r($headings, true) }}</pre>

 <p><strong>DEBUG - PREVIEW ROWS:</strong></p>
 <pre>{{ print_r($previewRows, true) }}</pre>

 <form id="formImport" action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <input type="hidden" name="archivo_path" value="{{ $path ?? '' }}">

  <table class="preview-table" role="grid" aria-label="Vista previa del archivo Excel">
   <thead>
    <tr>
  @foreach ($headings as $index => $heading)
    <th scope="col">
  {{ $heading ?: '—' }}
  <br>
  <select name="column_map[{{ $index }}]" required aria-label="Mapear columna {{ $heading }}">
   <option value="">-- No importar --</option>
   @foreach ($validColumns as $columnValue => $columnLabel)
    <option value="{{ $columnValue }}">{{ $columnLabel }}</option>
   @endforeach
  </select>
    </th>
  @endforeach
    </tr>
   </thead>
   <tbody>
    @foreach ($previewRows as $row)
  <tr>
   @foreach ($row as $cell)
    <td>{{ $cell !== null && $cell !== '' ? $cell : '—' }}</td>
   @endforeach
  </tr>
    @endforeach
   </tbody>
  </table>

  <button type="submit" class="btn btn-success" style="margin-top: 15px;">Confirmar Importación</button>
 </form>

@endsection