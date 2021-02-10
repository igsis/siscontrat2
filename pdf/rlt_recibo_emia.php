<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();


class PDF extends FPDF
{
}

$idParcela = $_POST['idParcela'];
$idPedido = $_POST['idPedido'];
$pedido = $con->query("SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 3 AND publicado = 1")->fetch_array();
$idEC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('emia_contratacao', 'id', $idEC);
$parcela = recuperaDados('emia_parcelas','id', $idParcela);


$cargo = recuperaDados('emia_cargos', 'id', $contratacao['emia_cargo_id']);

$enderecoArray = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);
if ($enderecoArray == NULL) {
    $endereco = "Não cadastrado";
} else {
    $endereco = $enderecoArray['logradouro'] . ", " . $enderecoArray['numero'] . " " . $enderecoArray['complemento'] . " / - " . $enderecoArray['bairro'] . " - " . $enderecoArray['cidade'] . " / " . $enderecoArray['uf'];
}

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$ano = date('Y', strtotime($parcela['data_pagamento']));

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetTitle("Recibo");

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(180, 15, utf8_decode("RECIBO"), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(14, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(120, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(14, $l, 'Objeto:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(180, $l, utf8_decode($cargo['cargo']), 0, 'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(14, $l, 'C.C.M.:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(40, $l, utf8_decode(checaCampo($pessoa['ccm'])), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(21, $l, utf8_decode('Passaporte:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['passaporte']), 0, 0, 'L');

} else {
    $pdf->Cell(8, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode(checaCampo($pessoa['rg'])), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(45, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');
}

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(160, $l, utf8_decode($endereco), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(23, $l, 'Telefone(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(95, $l, utf8_decode($tel), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(13, $l, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(168, $l, utf8_decode($pessoa['email']), 0, 'L', 0);


$pdf->Ln(16);

$pdf->SetX($x);
$pdf->MultiCell(180, $l, utf8_decode("Atesto que recebi da Prefeitura do Múnicípio de São Paulo - Secretaria Municipal de Cultura a importância de R$ " . dinheiroParaBr($parcela['valor']) . " (" . valorPorExtenso($parcela['valor']) . " ) referente ao período de " . exibirDataBr($parcela['data_inicio']) . " à " . exibirDataBr($parcela['data_fim']) . " como " . $cargo['cargo'] . "."), 0, 'L', 0);

$pdf->Ln(16);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(180, $l, utf8_decode("São Paulo, _______ de ________________________ de " . $ano . "."));

$pdf->Ln(16);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->MultiCell(180, $l, utf8_decode("OBSERVAÇÃO: A validade deste recibo está condicionada ao respectivo depósito do pagamento na conta corrente indicada pelo Artista."));

$pdf->SetXY($x, 262);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, $l, utf8_decode($pessoa['nome']), 'T', 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, $l, "RG: " . $pessoa['rg'], 0, 0, 'L');

$pdf->Output();
?>



