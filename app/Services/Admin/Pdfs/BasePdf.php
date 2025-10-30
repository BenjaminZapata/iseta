<?php

namespace App\Services\Admin\Pdfs;

use TCPDF;

abstract class BasePdf
{
    protected TCPDF $pdf;

    public function __construct()
    {
        $this->pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $this->pdf->SetCreator('Laravel');
        $this->pdf->SetAuthor('Dirección General de Cultura y Educación');
        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->AddPage();
    }

    abstract public function build($data): void;

    public function getPdf(): TCPDF
    {
        return $this->pdf;
    }
}