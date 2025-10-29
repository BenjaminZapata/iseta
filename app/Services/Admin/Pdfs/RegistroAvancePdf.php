<?php
namespace App\Services\Admin\Pdfs;
use Log;
use TCPDF;

class RegistroAvancePdf extends BasePdf {

	public function build($cursada): void {
		$cursadas = $cursada->cursadas()->with('alumno')->get();
		Log::info("Cursadas: {$cursadas}");
		$pdf = $this->pdf;
		$startX = $pdf->GetX();
		$startY = $pdf->GetY();
		// ---------------------
		// ENCABEZADO INICIAL
		// ---------------------
		$pdf->SetFont('helvetica', '', 10);

		// Primera fila (datos de institución)
		$pdf->Image(public_path('img/g22.svg'), 10, 10, 20, 20);

		$pdf->SetXY(0, 10);
		$pdf->writeHTMLCell(
			50, // ancho restante de la hoja A4 horizontal
			20, // alto aprox del bloque
			"33",
			"13",
			'<b>Dirección General de Cultura y Educación</b>
			<br>Gobierno de la Provincia<br>de Buenos Aires
			<br><br>Subsecretaría de Educación',
			0, 'R', 0, 1, '', '', true
		);
		
		//AÑO DE CURSADA
		$pdf->SetXY(10 + 40 + 5, 12);
		$pdf->setFont('helvetica', 'B', 10);
		$pdf->MultiCell(0, 0, 'A17a', 0, 'C', 0, 1, '250', '', true);
		$pdf->MultiCell(
			88, // ancho restante de la hoja A4 horizontal
			20, // alto aprox del bloque
			"AÑO:     {$cursada->anio_cursada}",
			0, 'R', 0, 1, '159.2', '44.2', true
		);
		$pdf->MultiCell(
			88, // ancho restante de la hoja A4 horizontal
			20, // alto aprox del bloque
			"DIRECCIÓN DE EDUCACIÓN SUPERIOR",
			0, 'R', 0, 1, '160', '20', true
		);
		$pdf->MultiCell(
			184, // ancho restante de la hoja A4 horizontal
				20, // alto aprox del bloque
				"________________________________________________",
				0, 'R', 0, 1, '90.5', '21', true
		);
		$pdf->MultiCell(
			88, // ancho restante de la hoja A4 horizontal
				20, // alto aprox del bloque
				"INSTITUTO SUPERIOR DE FORMACIÓN",
				0, 'R', 0, 1, '159.2', '25', true
		);
		$pdf->setFont('helvetica', '', 10);
		$pdf->MultiCell(0, 0, 'DOCENTE y/o TÉCNICA Nº ISETA', 0, 'C', 0, 1, '117.2', '28.5', true);
		$pdf->MultiCell(
			184, // ancho restante de la hoja A4 horizontal
				20, // alto aprox del bloque
				"________________________________________________",
				0, 'R', 0, 1, '90.5', '29', true
		);
		$pdf->SetY(38);
		// Título centrado
		$pdf->Ln(3);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->Cell(0, 10, 'REGISTRO DE AVANCE ACADÉMICO DE LOS ALUMNOS', 0, 1, 'C');
		$pdf->Ln(3);
		$pdf->SetXY(39, 53);
		// ---------------------
		// DATOS DE CABECERA
		// ---------------------
		$pdf->SetFont('helvetica', '', 10);
		$pdf->MultiCell(90, 8, "Carrera: {$cursada->carrera->nombre}", 0, 'L', 0,1, '20', '49', true);
		$pdf->MultiCell(90, 8, "Asignatura: {$cursada->asignatura->nombre}", 0, 'L', 0, 0, '20', '', true);
		$pdf->MultiCell(90, 8, "Profesor/a: " . ($cursada->asignatura->profesor()->wherePivot('id_carrera', $cursada->carrera->id)->first()?->apellido ?? '') . " " . ($cursada->asignatura->profesor()->wherePivot('id_carrera', $cursada->carrera->id)->first()?->nombre ?? ''), 0, 'L', 0, 0, '100', '', true);
		$pdf->Ln(3);

		// ---------------------
		// TABLA PRINCIPAL
		// ---------------------
		$pdf->SetFont('helvetica', 'B', 8);


		$pdf->setXY(22, 63);
		// FILA 1 – ENCABEZADOS SUPERIORES
		$pdf->SetFillColor(240, 240, 240);
		$pdf->MultiCell(4.5, 10, "N° de Orden", 1, 'C', 0, 0, '', '', true);
		$pdf->Cell(39, 32, "Apellido y\nnombres", 1, 0, 'C', 0);
		$pdf->MultiCell(20, 32, "Información\ncualitativa", 1, 'C', '0', 0, '', '', true,0,false,true, 31, valign: 'M');

		// Primer cuatrimestre
		$pdf->Cell(39, 9, "PRIMER CUATRIMESTRE", 1, 0, 'C', 0);
		// Segundo cuatrimestre
		$pdf->MultiCell(39, 9, 'SEGUNDO CUATRIMESTRE', 1, 'C', 0, 0, '', '', true,0,false,true, 10, valign: 'M');
		// Asistencia
		$pdf->MultiCell(10, 32, "ASI\nSTE\nNCI\nA", 1, 'C', '0', 0,"", '', true,0,false,true, 31, valign: 'M');
		// Recuperatorios
		$pdf->Cell(39, 9, "RECUPERATORIOS", 1, 0, 'C', 0);
		// Situación final
		$pdf->Cell(39, 9, "Situación FINAL", 1, 0, 'C', 0);
		// Observaciones
		$pdf->Cell(29, 32, "OBSERVACIONES", 1, 0, 'C', 0);

		// FILA 2 – SUBENCABEZADOS
		$pdf->SetFont('helvetica', 'B', 8);
		$pdf->setAbsXY(85.5, 72);
		// Primer cuatrimestre
		$pdf->MultiCell(9.75, 23, '', 1, 'C', 0, 0, '', '', true,0,false,true, 20, valign: 'M');
		$pdf->MultiCell(9.75, 23, '', 1, 'C', 0, 0, "", "", true,0,false,true, 20, valign: 'M');
		$pdf->MultiCell(9.75, 23, '', 1, 'C', 0, 0, '', '', true,0,false,true, 20, valign: 'M');
		$pdf->MultiCell(9.75, 23, "Nota Final\n1° Cuat.", 1, 'C', '0', 0, '', '', true,0,false,true, 20, valign: 'M');
		// Segundo cuatrimestre
		$pdf->MultiCell(9.75, 23, '', 1, 'C', 0, 0, '', '', true,0,false,true, 20, valign: 'M');
		$pdf->MultiCell(9.75, 23, '', 1, 'C', 0, 0, '', '', true,0,false,true, 20, valign: 'M');
		$pdf->MultiCell(9.75, 23, '', 1, 'C', 0, 0, '', '', true,0,false,true, 20, valign: 'M');
		$pdf->MultiCell(9.75, 23, "Nota Final\n2° Cuat.", 1, 'C', '0', 0, '', '', true,0,false,true, 20, valign: 'M');
		// Recuperatorios
		$pdf->setY(72);
		$pdf->SetX(173.5); // mover al bloque de recuperatorios
		$pdf->Cell(19.5, 23, 'Parciales', 1, 0, 'C');
		$pdf->Cell(19.5, 23, 'Asist.', 1, 0, 'C');
		// Situación final
		$pdf->Cell(19.5, 23, 'a FINAL', 1, 0, 'C');
		$pdf->Cell(19.5, 23, 'RECURSA', 1, 1, 'C');

		$pdf->SetFont('helvetica', '', 8);

		// ---------------------
		// FILAS DE ALUMNOS
		// ---------------------
		$pdf->SetY(95);
		foreach ($cursadas as $i => $cursada) {
			$pdf->SetX(22);
			$pdf->Cell(4.5, 8, $i+1, 1, 0, 'C');
			$pdf->Cell(39, 8, "{$cursada->alumno->apellido} {$cursada->alumno->nombre}", 1, 0, 'L');
			$pdf->Cell(20, 8, "{$cursada->alumno->dni}", 1, 0, 'L');

			// 1° Cuat.
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, "{$cursada->primer_cuatrimestre_nota}", 1, 0, 'C');

			// 2° Cuat.
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, "{$cursada->segundo_cuatrimestre_nota}", 1, 0, 'C');

			// Asistencia
			$pdf->Cell(10, 8, '', 1, 0, 'C');

			// Recuperatorios
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');

			// Situación final
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');

			// Observaciones
			$pdf->Cell(29, 8, "{$cursada->observaciones}", 1, 1, 'L');
			
		}
		$pdf->MultiCell(100, 8, 'Firma y aclaración del Profesor/a', 0, 'R', 0, 0, '176', '173', true,0,false,true, 20, valign: 'M');
		// ---------------------
	}
}