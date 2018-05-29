<?php

namespace App\Extension;

use setasign\fpdf\fpdf;

class PDFExtension extends \Twig_Extension
{

    /**
     * @return FPDF
     */
    public static function create_pdf()
    {
		$pdf = new \FPDF();
        $pdf->SetFont('Arial','B',16);
        
        return $pdf;
    }

    /**
     * @return FPDF
     */
    public static function add_page(
        $pdf
        )
    {
        $pdf->AddPage();

        $pdf->Line(105, 10, 105, 287);
        $pdf->Line(10, 100, 200, 100);
        $pdf->Line(10, 195, 200, 195);
    }

    /**
     * @return FPDF
     */
    public static function add_qrcode(
        $pdf,
        $postion,
        $nomBalise
        )
    {
        switch ($postion) {
            case 1:
                $pdf->Text(35, 12, $nomBalise);
                $pdf->Image('.\img\qrcode_' . $nomBalise . '.png', 17.5, 17, 80);
                break;
            case 2:
                $pdf->Text(140, 12, $nomBalise);
                $pdf->Image('.\img\qrcode_' . $nomBalise . '.png', 112.5, 17, 80);
                break;
            case 3:
                $pdf->Text(35, 108, $nomBalise);
                $pdf->Image('.\img\qrcode_' . $nomBalise . '.png', 17.5, 112, 80);
                break;
            case 4:
                $pdf->Text(140, 108, $nomBalise);
                $pdf->Image('.\img\qrcode_' . $nomBalise . '.png', 112.5, 112, 80);
                break;
            case 5:
                $pdf->Text(35, 203, $nomBalise);
                $pdf->Image('.\img\qrcode_' . $nomBalise . '.png', 17.5, 207, 80);
                break;
            case 6:
                $pdf->Text(140, 203, $nomBalise);
                $pdf->Image('.\img\qrcode_' . $nomBalise . '.png', 112.5, 207, 80);
                break;
        }
    }

    public static function telecharger_pdf(
        $pdf,
        $nomCourse
        )
    {
        $pdf->Output('D', $nomCourse . '.pdf', true);
    }
}
