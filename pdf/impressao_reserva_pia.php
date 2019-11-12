<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

class PDF extends FPDF
{
    function Header()
    {
        // Move to the right

        // Logo
        $this->Cell(80);
        $this->Image('../pdf/logo_smc.jpg', 170, 10);

        // Line break
        $this->Ln(20);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 15, "Clique aqui para ir para o ", 0, 0, 'L');
        $this->Image("../visual/images/logo_sei.jpg", 50, 286, 0, 5, "", "http://sei.prefeitura.sp.gov.br");
    }

}

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(29, $l, utf8_decode("Do Processo nº:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($pedido['numero_processo']), 0, 'L', 0);

$pdf->Ln(8);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(28, $l, 'INTERESSADO:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18,$l,"Programa:",0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20,$l, utf8_decode($programa['programa']), 0,0,'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21,$l,"Linguagem:", 0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20,$l, utf8_decode($linguagem['linguagem']), 0,0,'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11,$l,"Edital:", 0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20,$l, utf8_decode($programa['edital']), 0,0,'L');

$pdf->Ln(8);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(60, $l, utf8_decode('CONTABILIDADE'), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode("Sr(a). Responsável"), 0, 'L', 0);

$pdf->Ln(18);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode("O presente processo se trata de " . $pessoa['nome']
    . ", CONTRATAÇÃO COMO ARTISTA EDUCADOR DE " . $linguagem['linguagem']
    . " DO PROGRAMA DE " . $programa['programa']
    . " NOS TERMOS DO EDITAL " . $programa['edital']
    . ", no valor de R$ " . dinheiroParaBr($pedido['valor_total'])
    . " (" . valorPorExtenso($pedido['valor_total']) . " )"
    . ", conforme solicitação (link da solicitação), foram anexados os documentos necessários exigidos no edital, no ano de " . $contratacao['ano']), 0, 'L', 0);

$pdf->Ln(18);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode("Assim, solicito a reserva de recursos que deverá onerar a ação 6374 – Dotação 25.10.13.392.3001.6374"), 0, 'L', 0);

$pdf->Ln(10);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(155, $l, utf8_decode("Após, enviar para SMC/AJ, para prosseguimento."), 0, 'L', 0);

$pdf->Output();
?>

