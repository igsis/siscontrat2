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

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND id = '$idParcela' AND publicado = 1";
$query = mysqli_query($con, $sqlParcelas);
while ($parcela = mysqli_fetch_array($query)) {
    $valorParcela = $parcela['valor'];
    $datapgt = $parcela['data_pagamento'];
}

$sqlLocal = "SELECT local FROM locais l INNER JOIN emia_contratacao ec on ec.local_id = l.id WHERE ec.id = '$idEC' AND ec.publicado = 1";
$local = $con->query($sqlLocal)->fetch_array();

$ano = date('Y', strtotime("-3 hours"));

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 20);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetTitle(utf8_decode("Atestado Serviço"));

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(180, $l, utf8_decode("DEC/Escola Municipal de Iniciação Artística"), 0, 1, 'C');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("Viaduto do Chá, 15, - Bairro Centro - São Paulo/SP - CEP 01020-900"), 0, 1, 'C');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("Telefone:"), 0, 1, 'C');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("Ateste de recebimento da documentação"), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("Anexo I da Portaria SF nº 170, de 31 agosto de 2020"), 0, 1, 'C');

$pdf->SetX(40);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(140, $l, utf8_decode("Modelo de recebimento da documentação e ateste total/parcial de nota fiscal dentro/fora do prazo"), 0, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(180, $l, utf8_decode("Recebimento da Documentação"), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("Atesto:"), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("(X) o recebimento em ___/___/_____ de toda a documentação [TEXTO A INSERIR] prevista na Portaria SF nº 170/2020."), 0, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("(  ) o recebimento em ___/___/_____ de toda a documentação [TEXTO A INSERIR] prevista na Portaria SF nº 170/2020, ressalvado (s) [DOCS IRREGULARES]."), 0, 'L');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(180, $l, utf8_decode("Recebimento de material e/ou serviços"), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("Atesto:"), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("(  ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL] foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia ___/___/_____, dentro do prazo previsto."), 0, 'L');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("O prazo contratual é do dia ___/___/_____ até o dia ___/___/_____."), 0, 1, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("(  ) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL] foram entregues e/ou executados a contendo nos termos previstos no instrumento contratual( ou documento equivalente) no dia ___/___/_____, com atraso de ___ dias."), 0, 'L');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("O prazo contratual é do dia ___/___/_____ até o dia ___/___/_____."), 0, 1, 'L');

$pdf->AddPage();

$pdf->SetX($x);
$pdf->SetFillColor(240,240,240);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(180, $l, utf8_decode("INFORMAÇÕES COMPLEMENTARES"), 0, 1, 'L', true);

$pdf->Ln(2);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, 25, utf8_decode(""), 1, 1, 'L');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("À área gestora / de liquidação e pagamento."), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("Em virtude do detalhamento da Ação em 2019, informamos que o pagamento no valor de R$ 4.194,72 (quatro mil, cento e noventa e quatro reais e setenta e dois centavos) foi gasto na zona sul de São Paulo, rua Volkswagen, s/nº, Jabaquara, SP."), 0, 'L');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("Encaminho para prosseguimento"), 0, 1, 'L');


$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode("São Paulo, ____ de ________________________ de " . $ano . "."), 0, 1, 'C');

$pdf->Output();
?>

