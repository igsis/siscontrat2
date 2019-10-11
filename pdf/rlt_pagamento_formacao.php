<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

class PDF extends FPDF{
}

$idPedido = $_SESSION['idPedido'];
$idFC = $_SESSION['idFC'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$empenho = recuperaDados('pagamentos', 'pedido_id', $idPedido);



$data = date('Y-d-m');

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(180, 15, utf8_decode("PEDIDO DE PAGAMENTO"), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,$l,utf8_decode("Senhor(a)"),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,$l,utf8_decode("Chefe de Gabinete da"),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,$l,utf8_decode("Secretaria Municipal de Cultura"),0,1,'L');

//terminar daqui pra baixo
$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(160, $l, utf8_decode("Assunto:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(140,$l,utf8_decode("Pedido de Pagamento de R$". $valorParcela . ""),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, $l, utf8_decode("Nota de Empenho nº:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(120, $l, utf8_decode($empenho['nota_empenho']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(22, $l, utf8_decode("Emitida em:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(120, $l, utf8_decode(exibirDataBr($empenho['emissao_nota_empenho'])), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(49,$l,utf8_decode("Referente ao processo nº:"),0,0,'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(55,$l, utf8_decode($pedido['numero_processo']),0,'L',0);

$pdf->Ln(9);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40,$l, utf8_decode("São Paulo, " . exibirDataBr($data)));

$pdf->Ln(75);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(165,$l,utf8_decode($pessoa['nome']),'T',0,'L');

$pdf->Ln();

$pdf->SetX($x);
$pdf->Cell(7,$l,utf8_decode("RG:"),0,0,'L');
$pdf->SetFont('Arial', '',11);
$pdf->Cell(40,$l,utf8_decode($pessoa['rg']),0,0,'L');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(9,$l,utf8_decode("CPF:"),0,0,'L');
$pdf->SetFont('Arial', '',11);
$pdf->Cell(40,$l,utf8_decode($pessoa['cpf']),0,0,'L');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(13,$l,utf8_decode("E-mail:"),0,0,'L');
$pdf->SetFont('Arial', '',11);
$pdf->Cell(40,$l,utf8_decode($pessoa['email']),0,0,'L');

$pdf->Ln(7);

$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20,$l,"Programa:",0,0,'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20,$l, utf8_decode($programa['programa']), 0,0,'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(23,$l,"Linguagem:", 0,0,'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20,$l, utf8_decode($linguagem['linguagem']), 0,0,'L');
$pdf->SetFont('Arial', 'B', 11);

$pdf->Output();
?>

