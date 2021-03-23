<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../extras/DbModel.php");
require_once "../extras/Controllers.php";

$idEvento = $_GET['id'];

$eventoObj = new Controllers();
$evento = $eventoObj->recuperaEvento($idEvento);
$publicos = $eventoObj->recuperaPublico($idEvento);

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../visual/images/cultura_principal-horizontal.png', 20, 10);
        $this->Ln(20);
    }
}

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=5; //DEFINE A ALTURA DA LINHA
$f=10; //DEFINE TAMANHO DA FONTE

$pdf->SetXY( $x , 25 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

// Detalhes do evento
include_once "include/detalhes_evento.php";

// Detalhes da atração
if ($evento->tipo_evento_id == 1) {
    include_once "include/detalhes_atracao.php";
}

// Detalhes filme
if ($evento->tipo_evento_id == 2) {
    /*
 * INSERIR DADOS DE QUANDO NÃO FOR EVENTO
 */
    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 12);
    $pdf->Cell(180, $l, utf8_decode("FILME"), 'B', 1, 'C');
    $pdf->Ln();
}

// Detalhes ocorrência
include_once "include/detalhes_ocorrencia.php";

// arquivo comunicação produção
$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180, $l, utf8_decode("ARQUIVOS PARA COMUNICAÇÃO/PRODUÇÃO"), 'B', 1, 'C');
$arquivosComProd = $eventoObj->recuperaArquivoComProd($idEvento);
foreach ($arquivosComProd as $arquivo){
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'U', $f);
    $pdf->Cell(150, $l, utf8_decode(mb_strimwidth($arquivo->arquivo, 15, 60, "...")), 0, 1, 'L', false,"../uploadsdocs/" . $arquivo->arquivo);
}
$pdf->Ln(15);
// fim aqrquivo comunicação produção

// Detalhes pedido
if ($evento->contratacao = 1){
    include_once "include/detalhes_pedido.php";
}

$pdf->Output();