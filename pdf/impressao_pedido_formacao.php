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

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos','id',$idPedido);
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idPedido);
$idPf = $contratacao['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

$sei = null;

$idFc = $pedido['origem_id'];
$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFc'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

$carga = $_SESSION['formacao_carga_horaria'];

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
$pdf->MultiCell(12,$l,'Sr(a)',0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(60,$l,utf8_decode("Solicitamos a contratação a seguir:"),0,'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(23,$l,utf8_decode("Protocolo nº:"),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(120,$l,utf8_decode($contratacao['protocolo']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(29,$l,utf8_decode("Processo SEI nº:"),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(120,$l,utf8_decode($sei),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(30,$l,'Setor solicitante:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(120,$l,utf8_decode("Supervisão de Formação Cultural"),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,'Nome:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(40,$l,utf8_decode($pessoa['nome']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(9,$l,utf8_decode('CPF:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(168,$l,utf8_decode($pessoa['cpf']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(11,$l,'Email:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(168,$l,utf8_decode($pessoa['email']), 0, 'L', 0);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21,$l,'Telefone(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($tel),0,'L',0);

$pdf->Ln(5);
$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9,$l,'Ano:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($contratacao['ano']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25,$l,utf8_decode("Carga Horária:"), '0', '0','L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($carga),0,'L',0);


while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' | ';
}

$local = substr($local, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16,$l,'Local(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(165,$l,utf8_decode($local),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11,$l,'Valor:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode("R$ " . dinheiroParaBr($pedido['valor_total']) . " (" . valorPorExtenso($pedido['valor_total']) . " )"),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(38,$l,'Forma de Pagamento:', '0', '', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(145,$l,utf8_decode($pedido['forma_pagamento']),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22,$l,'Justificativa:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160,$l,utf8_decode($pedido['justificativa']),0,'L',0);

$pdf->Output();
?>

