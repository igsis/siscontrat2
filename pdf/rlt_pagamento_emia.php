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
$cargo = $con->query("SELECT cargo FROM emia_cargos WHERE id = '{$contratacao['emia_cargo_id']}'")->fetch_assoc()['cargo'];

$datas = recuperaDados('emia_parcelas', 'id', $idParcela);

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

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$sqlParcelas = "SELECT * FROM emia_parcelas WHERE id = '{$idParcela}' AND publicado = 1";
$query = mysqli_query($con, $sqlParcelas);
$parcela = mysqli_fetch_array($query);

$sqlLocal = "SELECT local FROM locais l INNER JOIN emia_contratacao ec on ec.local_id = l.id WHERE ec.id = '$idEC' AND ec.publicado = 1";
$local = $con->query($sqlLocal)->fetch_array();

$ano = date('Y', strtotime($datas['data_pagamento']));

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetTitle("Pagamento");

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

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16, $l, utf8_decode("Assunto:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(140, $l, utf8_decode("Pedido de Pagamento de R$ " . dinheiroParaBr($parcela['valor']) . " ( " . valorPorExtenso($parcela['valor']) . " )"), 0, 'L', 0);

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(13, $l, utf8_decode("Objeto:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(200, $l, utf8_decode("Oficineiro de $cargo da EMIA, da faixa etária de 05 a 12 anos."), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, utf8_decode("Local:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($local['local']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(34, $l, utf8_decode("Período de locação:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode("de " . exibirDataBr($parcela['data_inicio']) . " à " . exibirDataBr($parcela['data_fim'])), 0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(200, $l, utf8_decode("PAGAMENTO LIBERÁVEL A PARTIR DE " . exibirDataBr($parcela['data_pagamento']) . " MEDIANTE CONFIRMAÇÃO DA UNIDADE PROPONENTE."), 0, 'L', 0);

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
    $pdf->Cell(50, $l, utf8_decode(checaCampo($pessoa['rg'])), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(45, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');
}

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(36, $l, 'Data de Nascimento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(35, $l, utf8_decode($dataNascimento), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($endereco), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, 'Telefone(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(95, $l, utf8_decode($tel), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode($pessoa['email']), 0, "L", 0);

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->MultiCell(180, $l, utf8_decode("Venho, mui respeitosamente, requerer que o(a) senhor(a) se digne submeter a exame à decisão do órgão competente o pedido supra.
Declaro, sob as penas da Lei, não possuir débitos perante as Fazendas Públicas, em especial com a Prefeitura do Município de São Paulo.
Nestes termos, encaminho para deferimento."), 0, 'L', 0);


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

