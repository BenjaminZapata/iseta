<?php
namespace App\Services\Admin\Pdfs;
use TCPDF;

class RegistroAvancePdf extends BasePdf {

	public function build($data): void {
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
		
		$pdf->SetXY(10 + 40 + 5, 12);

		$pdf->MultiCell(
				235 - 40 - 5, // ancho restante de la hoja A4 horizontal
				20, // alto aprox del bloque
				"DIRECCIÓN DE EDUCACIÓN SUPERIOR\nINSTITUTO SUPERIOR DE FORMACIÓN\nDOCENTE y/o TÉCNICA Nº ........\n\nA17a",
				0, 'R', 0, 1, '', '', true
		);

		// Título centrado
		$pdf->Ln(3);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->Cell(0, 10, 'REGISTRO DE AVANCE ACADÉMICO DE LOS ALUMNOS', 0, 1, 'C');
		$pdf->Ln(3);

		// ---------------------
		// DATOS DE CABECERA
		// ---------------------
		$pdf->SetFont('helvetica', '', 10);
		$pdf->Cell(60, 8, 'Carrera: .......................................................', 1, 0, 'L');
		$pdf->Cell(90, 8, 'Asignatura: .......................................................', 1, 0, 'L');
		$pdf->Cell(90, 8, 'Profesor/a: .......................................................', 1, 1, 'L');
		$pdf->Ln(3);

		// ---------------------
		// TABLA PRINCIPAL
		// ---------------------
		$pdf->SetFont('helvetica', 'B', 8);

		// FILA 1 – ENCABEZADOS SUPERIORES
		$pdf->SetFillColor(240, 240, 240);
		$pdf->MultiCell(4.5, 10, "N° de Orden", 1, 'C', 0, 0, '', '', true);
		$pdf->Cell(30, 32, "Apellido y\nnombres", 1, 0, 'C', 0);
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
		$pdf->Cell(20, 14, "OBSERVACIONES", 1, 0, 'C', 0);

		// FILA 2 – SUBENCABEZADOS
		$pdf->SetFont('helvetica', 'B', 8);
		$pdf->setAbsXY(9.5 + 30 + 25, 70);
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
		$pdf->setY(70);
		$pdf->SetX(152.5); // mover al bloque de recuperatorios
		$pdf->Cell(19.5, 23, 'Parciales', 1, 0, 'C');
		$pdf->Cell(19.5, 23, 'Asist.', 1, 0, 'C');
		// Situación final
		$pdf->Cell(19.5, 23, 'a FINAL', 1, 0, 'C');
		$pdf->Cell(19.5, 23, 'RECURSA', 1, 1, 'C');

		$pdf->SetFont('helvetica', '', 8);

		// ---------------------
		// FILAS DE ALUMNOS
		// ---------------------
		$pdf->SetY(93);
		for ($i = 1; $i <= 10; $i++) {
			$pdf->Cell(4.5, 8, $i, 1, 0, 'C');
			$pdf->Cell(30, 8, '', 1, 0, 'L');
			$pdf->Cell(20, 8, '', 1, 0, 'L');

			// 1° Cuat.
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');

			// 2° Cuat.
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');
			$pdf->Cell(9.75, 8, '', 1, 0, 'C');

			// Asistencia
			$pdf->Cell(10, 8, '', 1, 0, 'C');

			// Recuperatorios
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');

			// Situación final
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');
			$pdf->Cell(19.5, 8, '', 1, 0, 'C');

			// Observaciones
			$pdf->Cell(20, 8, '', 1, 1, 'L');
		}

		// ---------------------
	}
}