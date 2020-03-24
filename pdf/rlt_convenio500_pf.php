<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();

class PDF extends FPDF{}

$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);


$ano = date('Y');

if($pessoa['passaporte'] != NULL){
    $trecho_rg_cpf_passaporte = ", Passaporte: " . $pessoa['passaporte'];
    $trecho_texto = "do Passaporte original";
}else{
    $trecho_rg_cpf_passaporte = ", RG: " . $pessoa['rg'] . ", CPF: " . $pessoa['cpf'];
    $trecho_texto = "de RG e CPF originais";
}

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=8; //DEFINE A ALTURA DA LINHA

$pdf->SetXY( $x , 30 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,utf8_decode('DECLARAÇÃO DE CONDIÇÕES PARA PAGAMENTO'),0,1,'C');

$pdf->Ln(3);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(167,$l,utf8_decode("Eu, ".$pessoa['nome'] . $trecho_rg_cpf_passaporte .", declaro para os devidos fins que não possuo conta no Banco do Brasil."));

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(167,$l,utf8_decode("Por se tratar de uma contratação de natureza eventual e não continuada e o cachê não exceder R$ 5.000,00 (cinco mil reais), solicito que o pagamento seja efetuado através de Ordem de Pagamento ou Ordem Bancária/Contra Recibo, através de recursos 500, conforme art. 2º da portaria SF 255/15."));

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(167,$l,utf8_decode("Estou ciente que o pagamento pode ser retirado no guichê do caixa, em qualquer agência do Bando do Brasil S.A, mediante a apresentação " . $trecho_texto . ", ficando disponível pelo período de 30 dias após a realização do crédito."));

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->Cell(180,$l,utf8_decode("São Paulo, _________ de ________________________________ de "."$ano"."."),0,0,'L');

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);

if($pessoa['passaporte'] != NULL){
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
}else{
    $pdf->Cell(100, 4, "RG: " . $pessoa['rg'], 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');    
}


$pdf->Output();
?>
