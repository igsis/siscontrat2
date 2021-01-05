<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../pdf/logo_smc.jpg', 25, 10);
        $this->Ln(20);
    }
}

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT p.id, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, p.numero_processo, l.extrato_liquidacao, l.retencoes_inss, l.retencoes_iss, l.retencoes_irrf FROM pedidos AS p INNER JOIN eventos AS e ON p.origem_id = e.id INNER JOIN liquidacao l on p.id = l.pedido_id WHERE e.publicado = 1 AND p.publicado = 1 AND p.id = '$idPedido'")->fetch_array();


// GERANDO O PDF:
$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 25;
$l = 6; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 45);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetTitle("Recibo Liquidação", true);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(160, 5, utf8_decode("RECIBO DE ENTREGA DE NOTA DE LIQUIDAÇÃO"), 0, 1, 'C');

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(160, $l, utf8_decode("Recebi nesta data, da Secretaria Municipal de Cultura, cópias dos seguintes documentos, conforme consta no processo nº: " . $pedido['numero_processo'] . ""));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(10, 5, utf8_decode("(    )"), 0, 0, 'C');
$pdf->MultiCell(160, $l, utf8_decode("Extrato de Liquidação e Pagamento nº: " . $pedido['extrato_liquidacao']));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(10, 5, utf8_decode("(    )"), 0, 0, 'C');
$pdf->MultiCell(160, $l, utf8_decode("Retenções de I.N.S.S. - Guia de Recolhimento ou Depósito da Prefeitura do Município de São Paulo nº: " . $pedido['retencoes_inss']));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(10, 5, utf8_decode("(    )"), 0, 0, 'C');
$pdf->MultiCell(160, $l, utf8_decode("Retenções de I.S.S. - Documento de Arrecadação de Tributos Imobiliários - DARM n.º: " . $pedido['retencoes_iss']));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(10, 5, utf8_decode("(    )"), 0, 0, 'C');
$pdf->MultiCell(160, $l, utf8_decode("Retenções de I.R.R.F. - Guia Recibo de Recolhimento ou Depósito nº: " . $pedido['retencoes_irrf']));

$pdf->Ln();
$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(160, $l, utf8_decode("Em, ______ de _______________________ de " . date('Y') . "."));

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(160, $l, utf8_decode("Assinatura: ____________________________________________"));

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

if ($pedido['pessoa_tipo_id'] == 1) {
    $idPf = $pedido['pessoa_fisica_id'];
    $pf = $con->query("SELECT nome, rg, passaporte,cpf, ccm FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_array();

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(14, $l, utf8_decode('Nome:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pf['nome']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);

    if ($pf['passaporte'] != NULL) {
        $pdf->Cell(23, $l, utf8_decode('Passaporte:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, $l, utf8_decode($pf['passaporte']), 0, 0, 'L');
    } else {
        $pdf->Cell(9, $l, utf8_decode('RG:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(150, $l, utf8_decode($pf['rg']), 0, 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(10, $l, utf8_decode('CPF:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(150, $l, utf8_decode($pf['cpf']), 0, 1, 'L');
    }

    $pdf->Ln();

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(12, $l, utf8_decode('CCM:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pf['ccm'] ? "" : "Não cadastrado"), 0, 1, 'L');
} else {
    $idPj = $pedido['pessoa_juridica_id'];
    $pj = $con->query("SELECT razao_social, cnpj, ccm, representante_legal1_id, representante_legal2_id FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_array();
    $idRep1 = $pj['representante_legal1_id'];
    $idRep2 = $pj['representante_legal2_id'] ?? "";
    $rep1 = $con->query("SELECT nome FROM representante_legais WHERE id = '$idRep1'")->fetch_array();
    $rep2 = $con->query("SELECT nome FROM representante_legais WHERE id = '$idRep2'")->fetch_array();

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(27, $l, utf8_decode('Razão Social:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pj['razao_social']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(14, $l, utf8_decode('CNPJ:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pj['cnpj']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(12, $l, utf8_decode('CCM:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pj['ccm'] ? "" : "Não cadastrado"), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(37, $l, utf8_decode('Responsável (eis):'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(143, $l, utf8_decode($rep1['nome']), 0, 1, 'L');

    if($idRep2 != ""):
        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(37, $l, '', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(143, $l, utf8_decode($rep2['nome'] ?? NULL), 0, 1, 'L');
    endif;
}

$pdf->Output();