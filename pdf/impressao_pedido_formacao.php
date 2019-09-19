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
$mensagem = "Solicitamos a contratação a seguir:";
$mensagemPedido = "Protocolo nº:";
$mensagemSEI = "Processo SEI nº:";
$mensagemCarga = "Carga Horária:";
$valor = "R$1.444,85";
$setor = "Supervisão de Formação Cultural";
$teste = null;
$idFc = $contratacao['origem_id'];
$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFc'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

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
$pdf->MultiCell(60,$l,utf8_decode($mensagem),0,'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(23,$l,utf8_decode($mensagemPedido),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(120,$l,utf8_decode($contratacao['protocolo']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(29,$l,utf8_decode($mensagemSEI),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(120,$l,utf8_decode($teste),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(30,$l,'Setor solicitante:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(120,$l,utf8_decode($setor),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(11,$l,'Nome:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(40,$l,utf8_decode($teste),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(9,$l,utf8_decode('CPF:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(168,$l,utf8_decode($teste),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(11,$l,'Email:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(168,$l,utf8_decode($teste), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16,$l,'Telefone:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($teste),0,'L',0);

$pdf->Ln(5);
$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(8,$l,'Ano:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($teste),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25,$l,utf8_decode($mensagemCarga), '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($teste),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11,$l,'Local:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($contratacao['local']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11,$l,'Valor:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($valor),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(38,$l,'Forma de Pagamento:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($pedido['forma_pagamento']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22,$l,'Justificativa:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168,$l,utf8_decode($pedido['justificativa']),0,'L',0);

$pdf->Output();
?>