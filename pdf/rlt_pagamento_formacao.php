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
$pedido = $con->query("SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 2 AND publicado = 1")->fetch_array();
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);

$ano = date('Y', strtotime("-3 hours"));

$enderecoArray = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);
if ($enderecoArray == NULL) {
    $endereco = "Não cadastrado";
} else {
    $endereco = $enderecoArray['logradouro'] . ", " . $enderecoArray['numero'] . " " . $enderecoArray['complemento'] . " / - " . $enderecoArray['bairro'] . " - " . $enderecoArray['cidade'] . " / " . $enderecoArray['uf'];
}

if ($pessoa['data_nascimento'] == '0000-00-00') {
    $dataNascimento = "Não cadastrado";
} else {
    $dataNascimento = exibirDataBr($pessoa['data_nascimento']);
}

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
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(180, $l, utf8_decode("Senhor(a)"), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(180, $l, utf8_decode("Chefe de Gabinete da"), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(180, $l, utf8_decode("Secretaria Municipal de Cultura"), 0, 1, 'L');

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND id = '$idParcela' AND publicado = 1";
$query = mysqli_query($con, $sqlParcelas);
while ($parcela = mysqli_fetch_array($query)) {
    $valorParcela = $parcela['valor'];
    $datapgt = $parcela['data_pagamento'];
}

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16, $l, utf8_decode("Assunto:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(140, $l, utf8_decode("Pedido de Pagamento de R$ " . $valorParcela . " (" . valorPorExtenso($valorParcela) . " )"), 0, 'L', 0);

$linguagem = recuperaDados('linguagens', 'id', $contratacao['linguagem_id'])['linguagem'];

$programa = recuperaDados('programas', 'id', $contratacao['programa_id'])['programa'];

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(19, $l, utf8_decode("Programa:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, $l, utf8_decode($programa), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, utf8_decode("Linguagem:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(65, $l, utf8_decode($linguagem), 0, 0, 'L');

$pdf->Ln(7);

$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFC'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' | ';
}

$local = substr($local, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(17, $l, utf8_decode("Local(s):"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($local), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, $l, utf8_decode("Período de locação:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode(retornaPeriodoFormacao_Emia($contratacao['form_vigencia_id'], "formacao")), 0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(200, $l, utf8_decode("PAGAMENTO LIBERÁVEL A PARTIR DE " . exibirDataBr($datapgt) . " MEDIANTE CONFIRMAÇÃO DA UNIDADE PROPONENTE."), 0, 'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(21, $l, utf8_decode('Passaporte:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['passaporte']), 0, 0, 'L');

} else {
    $pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['rg'] ?? "Não cadastrado"), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(45, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');
}

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, $l, 'Data de Nascimento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($dataNascimento), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($endereco), 0, 'L', 0);

$pdf->Ln(7);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, 'Telefone(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(95, $l, utf8_decode($tel), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(168, $l, utf8_decode($pessoa['email']), 0, 0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("Venho, mui respeitosamente, requerer que o(a) senhor(a) se digne submeter a exame à decisão do órgão competente o pedido supra.
Declaro, sob as penas da Lei, não possuir débitos perante as Fazendas Públicas, em especial com a Prefeitura do Município de São Paulo.
Nestes termos, encaminho para deferimento."));


$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("São Paulo, _______ de ________________________ de " . $ano . "."));

$pdf->SetXY($x, 262);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, $l, utf8_decode($pessoa['nome']), 'T', 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);

if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
} else {
    $rg = "RG: " . checaCampo($pessoa['rg']);
    $pdf->Cell(100, 4, utf8_decode($rg), 0, 1, 'L');
}

$pdf->Output();
?>

