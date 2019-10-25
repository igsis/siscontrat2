<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

class PDF extends FPDF
{
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 15, "Clique aqui para ir para o ", 0, 0, 'L');
        $this->Image("../visual/images/logo_sei.jpg", 50, 286, 0, 5, "", "http://sei.prefeitura.sp.gov.br");
    }
}

$idParcela = $_POST['idParcela'];
$idPedido = $_SESSION['idPedido'];
$idFC = $_SESSION['idFC'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$fiscal = recuperaDados('usuarios', 'id', $contratacao['fiscal_id']);
$suplente = recuperaDados('usuarios', 'id', $contratacao['suplente_id']);

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(170,5,utf8_decode("ATESTADO DE CONFIRMAÇÃO DE SERVIÇOS"),0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(200, $l, utf8_decode("Informamos que os serviços prestados por: " . $pessoa['nome']), 0, 'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22, $l, utf8_decode("PROCESSO:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($pedido['numero_processo']), 0, 'L', 0);

$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(17, $l, utf8_decode("EVENTO:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(18, $l, utf8_decode($programa['programa'] . " - " .$linguagem['linguagem']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9, $l, utf8_decode("Ano:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($contratacao['ano']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode("(     ) NÃO FORAM REALIZADOS"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(120, $l, utf8_decode("( X ) FORAM REALIZADOS A CONTENTO"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180, $l, utf8_decode("(     ) NÃO FORAM REALIZADOS A CONTENTO, PELO SEGUINTE MOTIVO:"), 0, 'L', 0);

$pdf->Ln(9);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(120, $l, utf8_decode("DADOS DO SERVIDOR (A) QUE ESTÁ CONFIRMANDO OU NÃO A REALIZAÇÃO DOS SERVIÇOS:"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->Cell(15, $l, utf8_decode("FISCAL:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40,$l,utf8_decode($fiscal['nome_completo']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(7, $l, utf8_decode("RF:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40,$l,utf8_decode($fiscal['rf_rg']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, utf8_decode("SUPLENTE:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40,$l,utf8_decode($suplente['nome_completo']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(7, $l, utf8_decode("RF:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40,$l,utf8_decode($suplente['rf_rg']),0,'L',0);

$pdf->Ln(9);

$idVigencia = $contratacao['form_vigencia_id'];

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND id = '$idParcela'";
$query = mysqli_query($con,$sqlParcelas);
while($parcela = mysqli_fetch_array($query))
{
    if($parcela['valor'] > 0)
    {
        $datapgt = exibirDataBr($parcela['data_pagamento']);
    }
}

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(34, $l, utf8_decode("Data de Pagamento:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180,$l,utf8_decode($datapgt),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->MultiCell(150,$l,utf8_decode("À área gestora de liquidação e pagamento encaminho para prosseguimento."),0,'L',0);

$pdf->Output();
?>


