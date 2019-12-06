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
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idEC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('emia_contratacao', 'id', $idEC);

$cargo = recuperaDados('emia_cargos','id',$contratacao['emia_cargo_id']);

$ano = date('Y',strtotime("-3 hours"));

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(180, 15, utf8_decode("RECIBO"), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(14, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(180, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(14, $l, 'Cargo:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(180, $l, utf8_decode($cargo['cargo']), 0, 'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(14, $l, 'C.C.M.:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(40, $l, utf8_decode($pessoa['ccm']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(8, $l, utf8_decode('RG:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(50, $l, utf8_decode($pessoa['rg']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(10, $l, utf8_decode('CPF:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(5, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');

$pdf->Ln(7);

$endereco = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(160, $l, utf8_decode($endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " .$endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf']), 0, 'L', 0);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(23, $l, 'Telefone(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(95, $l, utf8_decode($tel), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(13, $l, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(168, $l, utf8_decode($pessoa['email']), 0, 'L',0);

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND id = '$idParcela'";
$query = mysqli_query($con,$sqlParcelas);
while($parcela = mysqli_fetch_array($query))
{
    if($parcela['valor'] > 0)
    {
        $valorParcela = $parcela['valor'];
    }
}

$pdf->Ln(16);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Atesto que recebi da Prefeitura do Múnicípio de São Paulo - Secretaria Municipal de Cultura a importância de R$ ". $valorParcela . " (" . valorPorExtenso($valorParcela) . " ) .  referente ao período " . retornaPediodoEmia($contratacao['emia_vigencia_id']) . " da " . $cargo['cargo']),0,'L',0);

$pdf->Ln(16);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(180,$l,utf8_decode("São Paulo, _______ de ________________________ de ".$ano."."));

$pdf->Ln(16);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->MultiCell(180,$l,utf8_decode("OBSERVAÇÃO: A validade deste recibo está condicionada ao respectivo depósito do pagamento na conta corrente indicada pelo Artista."));

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 12);
$pdf->Cell(100,$l,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 12);
$pdf->Cell(100,$l,"RG: ".$pessoa['rg'],0,0,'L');

$pdf->Output();
?>



