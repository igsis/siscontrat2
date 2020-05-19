<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();


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
}

$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$idEC = $pedido['origem_id'];
$contratacao = recuperaDados('emia_contratacao', 'id', $idEC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
if($pessoa['nacionalidade_id'] != NULL){
    $nacionalidade = recuperaDados('nacionalidades', 'id', $pessoa['nacionalidade_id'])['nacionalidade'];
}else{
    $nacionalidade = "Não cadastrado";
}


$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

$idLocal = $contratacao['local_id'];
$sqlLocal = "SELECT local FROM locais WHERE id = '$idLocal'";
$local = $con->query($sqlLocal)->fetch_array();

$ano = date('Y');

$idVigencia = $contratacao['emia_vigencia_id'];

$carga = null;
$sqlCarga = "SELECT carga_horaria FROM emia_parcelas WHERE emia_vigencia_id = '$idVigencia'";
$queryCarga = mysqli_query($con,$sqlCarga);

while ($countt = mysqli_fetch_array($queryCarga))
    $carga += $countt['carga_horaria'];

$enderecoArray = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);
if($enderecoArray == NULL){
    $endereco = "Não cadastrado";
}else{
    $endereco = $enderecoArray['logradouro'] . ", " . $enderecoArray['numero'] . " " . $enderecoArray['complemento'] . " / - " .$enderecoArray['bairro'] . " - " . $enderecoArray['cidade'] . " / " . $enderecoArray['uf'];
}

$testaDrt = $con->query("SELECT drt FROM drts WHERE pessoa_fisica_id = $idPf");
if ($testaDrt->num_rows > 0) {
    while ($drtArray = mysqli_fetch_array($testaDrt)) {
        $drt = $drtArray['drt'];
    }
} else {
    $drt = "Não cadastrado";
}

$Observacao = "Todas as atividades dos programas da Supervisão de Formação são inteiramente gratuitas e é terminantemente proibido cobrar por elas sob pena de multa e rescisão de contrato.";
$sqlPenalidade = "SELECT texto FROM penalidades WHERE id = 20";
$penalidades = $con->query($sqlPenalidade)->fetch_array();


$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,5,'(A)',0,0,'L');
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(170,5,'CONTRATADO',0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(27, $l, utf8_decode("Nome Artístico:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($pessoa['nome_artistico']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(21, $l, utf8_decode('Passaporte:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['passaporte']), 0, 0, 'L');

} else {
    $pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg']), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(45, $l, utf8_decode($pessoa['cpf'] == NULL ? "Não cadastrado" : $pessoa['cpf']), 0, 0, 'L');
}

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, $l, 'Data de Nascimento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(25, $l, utf8_decode(exibirDataBr($pessoa['data_nascimento'])), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, $l, "Nacionalidade:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($nacionalidade),0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, $l, "CCM:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($pessoa['ccm'] == NULL ? "Não cadastrado" : $pessoa['cpf']),0 ,0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10,$l,'DRT:',0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40,$l,utf8_decode($drt), 0,'L',0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($endereco), 0, 'L', 0);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, 'Telefone(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode($tel), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode($pessoa['email']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->Cell(180,5,'','B',1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,10,'(B)',0,0,'L');
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(160,10,'PROPOSTA',0,0,'C');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,10,utf8_decode($contratacao['protocolo']),0,1,'R');

$pdf->Ln(6);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22, $l, 'Data/Perido:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, $l, utf8_decode(retornaPediodoEmia($contratacao['emia_vigencia_id'])), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, $l, utf8_decode("Carga Horária:"), '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(168, $l, utf8_decode($carga), 0, 0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16, $l, 'Local(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(165, $l, utf8_decode($local['local']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, 'Valor:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode("R$ " . dinheiroParaBr($pedido['valor_total']) . " (" . valorPorExtenso($pedido['valor_total']) . " )"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(38, $l, 'Forma de Pagamento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(122, $l, utf8_decode($pedido['forma_pagamento']));

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22, $l, 'Justificativa:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(155, $l, utf8_decode($pedido['justificativa']));

//RODAPÉ PERSONALIZADO
$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
if($pessoa['passaporte'] != NULL){
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
}else{
    $rg = $pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg'];
    $rg = "RG: " . $rg;
    $pdf->Cell(100, 4,  utf8_decode($rg), 0, 1, 'L');
}

$pdf->AddPage('','');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,$l,'(C)',0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(160,$l,utf8_decode('OBSERVAÇÃO'),0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(155, $l, utf8_decode($Observacao),0, 'J', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 4, utf8_decode($penalidades['texto']),0, 'J', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(180,$l,utf8_decode("Data: _________ / _________ / " . $ano) . ".",0,0,'L');

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
if($pessoa['passaporte'] != NULL){
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
}else{
    $rg = $pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg'];
    $rg = "RG: " . $rg;
    $pdf->Cell(100, 4,  utf8_decode($rg), 0, 1, 'L');
}

$pdf->AddPage('','');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->MultiCell(180,5,"CRONOGRAMA",0,'C',0);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(140,5,utf8_decode($contratacao['cronograma']),0,'L',0);

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
if($pessoa['passaporte'] != NULL){
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
}else{
    $rg = $pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg'];
    $rg = "RG: " . $rg;
    $pdf->Cell(100, 4,  utf8_decode($rg), 0, 1, 'L');
}

$pdf->Output();
?>


