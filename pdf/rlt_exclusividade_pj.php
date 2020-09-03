<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();


class PDF extends FPDF
{
}

$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPj = $pedido['pessoa_juridica_id'];
$pessoa = recuperaDados('pessoa_juridicas', 'id', $idPj);
$rep = recuperaDados('representante_legais', 'id', $pessoa['representante_legal1_id']);

$lider = $con->query("SELECT p.nome, p.rg, p.cpf, p.passaporte FROM pessoa_fisicas AS p INNER JOIN lideres l on p.id = l.pessoa_fisica_id WHERE l.pedido_id = $idPedido")->fetch_array();

if ($lider['passaporte'] != NULL) {
    $trecho_rg_cpf_passaporte = ", Passaporte: " . $lider['passaporte'];
} else {
    $rg = $lider['rg'] == NULL ? "(Não cadastrado)" : $lider['rg'];
    $trecho_rg_cpf_passaporte = ", RG: " . $rg . ", CPF: " . $lider['cpf'];
}

$ano = date('Y');


// GERANDO O PDF:
$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 8; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 30);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(180, 5, utf8_decode('DECLARAÇÃO DE EXCLUSIVIDADE'), 0, 1, 'C');

$pdf->Ln(3);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(170, $l, utf8_decode("Eu, " . $lider['nome'] . $trecho_rg_cpf_passaporte . ", sob penas da lei, declaro que sou representado exclusivamente pela empresa " . $pessoa['razao_social'] . ", CNPJ " . $pessoa['cnpj'] . ""));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(167, $l, utf8_decode("Estou ciente de que o pagamento dos valores decorrentes dos serviços é de responsabilidade da minha representante, não me cabendo pleitear à Prefeitura quaisquer valores eventualmente não repassados."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(170, $l, utf8_decode($pessoa['razao_social'] . ", CNPJ " . $pessoa['cnpj'] . " representada por " . $rep['nome'] . ", RG " . $rep['rg'] . ", CPF " . $rep['cpf'] . " declara sob penas da lei ser representante de " . $lider['nome'] . "."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(170, $l, utf8_decode("Declaro, sob as penas da lei, que não sou servidor público municipal e que não me encontro em impedimento para contratar com a Prefeitura do Município de São Paulo / Secretaria Municipal de Cultura, mediante recebimento de cachê e/ou bilheteria, quando for o caso."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(170, $l, utf8_decode("Declaro, ainda, neste ato, que autorizo, a título gratuito, por prazo indeterminado, a Municipalidade de São Paulo, através da SMC, o uso da nossa imagem, voz e performance nas suas publicações em papel e qualquer mídia digital, streaming ou internet existentes ou que venha a existir como também para os fins de arquivo e material de pesquisa e consulta."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(170, $l, utf8_decode("A empresa fica autorizada a celebrar contrato, inclusive receber cachê e/ou bilheteria quando for o caso, outorgando quitação."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("São Paulo, _______ / _______ /" . "$ano" . "."), 0, 'L', 0);

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(100, 4, utf8_decode("Nome do Líder do Grupo: " . $lider['nome']), 'T', 1, 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
if ($lider['passaporte'] != NULL) { //testa e exibe o passaporte do lider, caso não haja passaporte exibe o rg juntamente do cpf
    $pdf->MultiCell(100, 4, "Passaporte: " . $lider['passaporte'], 0, 'L', 0);
} else {
    $rgLider = $lider['rg'] == NULL ? "Não cadastrado" : $lider['rg'];
    $pdf->MultiCell(100, 4, "RG: " . $rgLider, 0, 'L', 0);

    $pdf->SetX($x);
    $pdf->MultiCell(100, 4, "CPF: " . $lider['cpf'], 0, 'L', 0);
}

$pdf->Ln(12);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(100, 4, utf8_decode("Representante Legal: " . $rep['nome']), 'T', 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(100, 4, "RG: " . $rep['rg'], 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(100, 4, "CPF: " . $rep['cpf'], 0, 'L', 0);


$pdf->Output();
?>
