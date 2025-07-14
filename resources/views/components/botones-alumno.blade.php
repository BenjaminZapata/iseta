@php
use Illuminate\Support\Facades\Route;
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@if (Route::currentRouteName() === 'admin.alumnos.edit')
<div class="dropdown" style="position: relative;">
 <button class="btn_sky" onclick="toggleExportar()" type="button" style="color: black;">
  <i class="ti ti-file-download" style="font-size: 1.3em; margin-right: 8px;"></i>Exportar...
 </button>
 <div id="exportar-opciones" style="display: none; position: absolute; right: 0; bottom: 100%; background: white; border: 1px solid #ccc; padding: 8px; z-index: 99;">
  <a href="{{ route('admin.alumnos.analitico.pdf', ['alumno' => request()->route('alumno')]) }}">
   <button class="btn_sky" type="button" style="margin-right: 8px; width: 120px">
    <i class="ti ti-file-download" style="font-size: 1.3em; margin-right: 8px; "></i>Analítico
   </button>
  </a>
  <a href="{{ route('admin.alumnos.regular', ['alumno' => request()->route('alumno')]) }}">
   <button class="btn_sky" type="button" style="margin-right: 8px; width: 120px">
    <i class="ti ti-file-download" style="font-size: 1.3em; margin-right: 8px; "></i>Certificado
   </button>
  </a>
 </div>
</div>
@endif