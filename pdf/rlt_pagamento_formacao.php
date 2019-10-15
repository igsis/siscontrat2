<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

class PDF extends FPDF{
}

$idParcela = $_POST['idParcela'];
$idPedido = $_SESSION['idPedido'];
$idFC = $_SESSION['idFC'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$empenho = recuperaDados('pagamentos', 'pedido_id', $idPedido);

$ano = date('Y',strtotime("now"));

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

$idVigencia = $contratacao['form_vigencia_id'];

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND id = '$idParcela'";
$query = mysqli_query($con,$sqlParcelas);
while($parcela = mysqli_fetch_array($query))
{
    if($parcela['valor'] > 0)
    {
        $valorParcela = $parcela['valor'];
        $datapgt = $parcela['data_pagamento'];
    }
}

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16, $l, utf8_decode("Assunto:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(140,$l,utf8_decode("Pedido de Pagamento de R$ ". $valorParcela . " ( " . valorPorExtenso($valorParcela) . " )"),0,'L',0);

$pdf->Ln(2);

$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(19, $l, utf8_decode("Programa:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, $l, utf8_decode($programa['programa']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, utf8_decode("Linguagem:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(65, $l, utf8_decode($linguagem['linguagem']), 0, 0, 'L');

$pdf->Ln(7);

$idFc = $pedido['origem_id'];
$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFc'";
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
$pdf->MultiCell(200,$l,utf8_decode("PAGAMENTO LIBERÁVEL A PARTIR DE ". $datapgt ." MEDIANTE CONFIRMAÇÃO DA UNIDADE PROPONENTE."),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, $l, utf8_decode($pessoa['rg']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(5, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, $l, 'Data de Nascimento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(25, $l, utf8_decode(exibirDataBr($pessoa['data_nascimento'])), 0, 'L', 0);

$endereco = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " .$endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf']), 0, 'L', 0);

$pdf->Ln(7);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
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
$pdf->Cell(95, $l, utf8_decode($tel), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(168, $l, utf8_decode($pessoa['email']), 0, 0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(180,$l,utf8_decode("Venho, mui respeitosamente, requerer que o(a) senhor(a) se digne submeter a exame à decisão do órgão competente o pedido supra.
Declaro, sob as penas da Lei, não possuir débitos perante as Fazendas Públicas, em especial com a Prefeitura do Município de São Paulo.
Nestes termos, encaminho para deferimento."));


$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(180,$l,utf8_decode("São Paulo, _______ de ________________________ de ".$ano."."));

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,$l,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,$l,"RG: ".$pessoa['rg'],0,0,'L');

$pdf->Output();
?>

