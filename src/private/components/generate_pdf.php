<?php
if(isset($_POST['generate_pdf'])){
    $pdf_content = $_POST['pdf_content'];

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once 'tfpdf/tfpdf.php';
    $decodedContent = html_entity_decode($pdf_content);
    $decodedContent = str_replace(array("\r\n", "\r", "\n","\t"), ' ',$decodedContent);
    $decodedContent = str_replace('  ', ' ' ,$decodedContent);
    $decodedContent = str_replace('   ', ' ' ,$decodedContent);
    $decodedContent = str_replace('    ', ' ' ,$decodedContent);
    $decodedContent = ltrim($decodedContent,' ');
    class PDF extends tFPDF{
        function Header(){
            $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
            $this->SetFont('DejaVu','',10);
            $this->Cell(80); // Zväčšenie ľavého okraja na 100 jednotiek
            $this->Cell(20, 10, 'Manual', 1, 0, 'C');
            $this->Ln(20);
        }
    }
    $pdf = new PDF();
    //$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    //$pdf->SetFont('DejaVu','',10);

    
    $pdf->AddPage();
    $pdf->Write(10, $decodedContent);
    $pdf->Output();
}
?>
