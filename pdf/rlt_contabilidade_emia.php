<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

class PDF extends FPDF{

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 15, "Clique aqui para ir para o ", 0, 0, 'L');
        $this->Image("../visual/images/logo_sei.jpg", 50, 286, 0, 5, "", "http://sei.prefeitura.sp.gov.br");
    }
}
$idParcela = $_POST['idParcela'];
$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idEC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('emia_contratacao', 'id', $idEC);
$sqlLocal = "SELECT local FROM locais l INNER JOIN emia_contratacao ec on ec.local_id = l.id WHERE ec.id = '$idEC' AND ec.publicado = 1";
$local = $con->query($sqlLocal)->fetch_array();

$data = date("Y-m-d", strtotime("now"));

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(180, $l, utf8_decode("SMC - CONTABILIDADE"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(120, $l, utf8_decode("Sr.(a) Contador(a)"), 0, 'L', 0);

$pdf->Ln(20);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B',10);
$pdf->Cell(12,$l,utf8_decode("Nome:"),0, 0, 'L');
$pdf->SetFont('Arial', '',10);
$pdf->MultiCell(40,$l,utf8_decode($pessoa['nome']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B',10);
$pdf->Cell(9,$l,utf8_decode("CPF:"),0, 0, 'L');
$pdf->SetFont('Arial', '',10);
$pdf->MultiCell(40,$l,utf8_decode($pessoa['cpf']),0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B',10);
$pdf->Cell(10,$l,utf8_decode("Local:"),0, 0, 'L');
$pdf->SetFont('Arial', '',10);
$pdf->MultiCell(100,$l,utf8_decode($local['local']),0,'L',0);

$pdf->Ln(10);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Com base na Confirmação de Serviços (Documento SEI link ), atesto que foi efetivamente cumprido horas de trabalho durante o período supra citado."),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Em virtude do detalhamento da Ação em 2019, informamos que o pagamento  no valor de R$ 4.194,72 (quatro mil, cento e noventa e quatro reais e setenta e dois centavos) foi gasto na zona sul de São Paulo, rua Volkswagen, s/nº, Jabaquara, SP."),0,'L',0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Encaminhamos o presente para as providências necessárias relativas ao pagamento da parcela do referido processo."),0,'L',0);

$pdf->Output();
?>




