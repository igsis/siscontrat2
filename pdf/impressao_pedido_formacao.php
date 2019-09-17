<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

session_start();

class PDF extends FPDF{
    function Header()
    {
        // Move to the right

        // Logo
        $this->Image('../pdf/logo_smc.jpg',30, 10);

        // Line break
        $this->Ln(20);
    }
}
$teste = "123 TESTANDO";

$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=8; //DEFINE A ALTURA DA LINHA

$pdf->SetXY( $x , 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(180,15,utf8_decode("PEDIDO DE CONTRATAÇÃO DE PESSOA FÍSICA"),0,1,'C');

$pdf->Ln(5);

$pdf->SetX(160);


$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,'Nome:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(168,$l,utf8_decode($teste));

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(40,$l,utf8_decode($teste),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,'Email:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(168,$l,utf8_decode($teste),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16,$l,'Telefone:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($teste), 0, 'L', 0);


$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(20,$l,utf8_decode('Data de Nascimento:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
/*if($dataNascimento == "31/12/1969")
    $pdf->Cell(25,$l, " " ,0,1,'L');
else*/
$pdf->MultiCell(168,$l,utf8_decode($teste),0,'L',0);

$pdf->Output();
?>