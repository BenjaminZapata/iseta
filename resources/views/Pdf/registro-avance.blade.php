<style>
h3 { text-align: center; font-weight: bold; }
th, td {
  text-align: center;      /* 🔹 centra horizontalmente */
  vertical-align: middle;  /* 🔹 centra verticalmente */
  padding: 6px;            /* 🔹 controla espacio interno sin cambiar fuente */
  font-size: 14px;         /* 🔹 tamaño fijo de fuente */
  height: 35px;            /* 🔹 altura fija o ajustable de celda */
  overflow-wrap: break-word; /* 🔹 permite que el texto se ajuste dentro */
}
th {
  background-color: #f0f0f0;
}
table {
  border-collapse: collapse;
  width: 100%;
  table-layout: fixed; /* 🔹 hace que el ancho se distribuya sin afectar el texto */
}
</style>

<table border="0" cellspacing="0" cellpadding="2">
<tr>
    <td width="50%">
        <b>Dirección General de Cultura y Educación</b><br>
        Gobierno de la Provincia de Buenos Aires<br>
        Subsecretaría de Educación
    </td>
    <td width="50%" align="right">
        <b>DIRECCIÓN DE EDUCACIÓN SUPERIOR</b><br>
        INSTITUTO SUPERIOR DE FORMACIÓN<br>
        DOCENTE y/o TÉCNICA Nº ........<br><br>
        <b>A17a</b>
    </td>
</tr>
</table>

<h3>REGISTRO DE AVANCE ACADÉMICO DE LOS ALUMNOS</h3>

<table border="1" cellpadding="4">
<tr>
    <td width="15%"><b>Carrera:</b> .......................................................</td>
    <td width="50%"><b>Asignatura:</b> .......................................................</td>
    <td width="35%"><b>Profesor/a:</b> .......................................................</td>
</tr>
</table>
<br>

<table border="1" cellpadding="2">
<thead>
<tr>
    <th rowspan="2" width="2%">N°<br>de<br>Orden</th>
    <th rowspan="2" width="20%" style="text-align: center;">
        <div style="font-size:40pt">&nbsp;</div>
        Apellido y nombres
    </th>
    <th rowspan="2" width="10%">Información<br>cualitativa</th>
    <th colspan="3" width="18%">PRIMER CUATRIMESTRE</th>
    <th colspan="3" width="18%">SEGUNDO CUATRIMESTRE</th>
    <th rowspan="2" width="5%">ASISTENCIA</th>
    <th colspan="2" width="10%">RECUPERATORIOS</th>
    <th colspan="2" width="8%">Situación FINAL</th>
    <th width="7%">OBSERVACIONES</th>
</tr>
<tr align="center">
    <th width="5%">Parciales</th>
    <th width="5%">Asist.</th>
    <th width="8%">Nota Final<br>1° Cuat.</th>
    <th width="5%">Parciales</th>
    <th width="5%">Asist.</th>
    <th width="8%">Nota Final<br>2° Cuat.</th>
    <th width="5%">Parciales</th>
    <th width="5%">Asist.</th>
    <th width="4%">a FINAL</th>
    <th width="4%">RECURSA</th>
    <th></th>
</tr>
</thead>
<tbody>
@for ($i = 1; $i <= 10; $i++)
<tr>
    <td width="2%"></td>
    <td>
    </td>
    <td></td><td></td><td></td><td></td>
    <td></td><td></td><td></td><td></td>
    <td></td><td></td><td></td><td></td><td></td>
</tr>
@endfor
</tbody>
</table>
