<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

class PDF extends FPDF{}

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$idAtracao = $ocorrencia['atracao_id'];
$atracao = recuperaDados('atracoes', 'id', $idAtracao);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);


$ano = date('Y');

if($pessoa['passaporte'] != NULL){
    $trecho_rg_cpf_passaporte = ", Passaporte: " . $pessoa['passaporte'];
}else{
    $rg = $pessoa['rg'] == NULL ? "(Não cadastrado)" : $pessoa['rg'];
    $trecho_rg_cpf_passaporte = ", RG: " . $rg . ", CPF: " . $pessoa['cpf'];
}

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=8; //DEFINE A ALTURA DA LINHA

$pdf->SetXY( $x , 30 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetTitle("Exclusividade PF");

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,utf8_decode('DECLARAÇÃO DE EXCLUSIVIDADE'),0,1,'C');

$pdf->Ln(3);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(167,$l,utf8_decode("Eu, ".$pessoa['nome'] . $trecho_rg_cpf_passaporte .", sob penas da lei, sob as penas da Lei, que não sou servidor público municipal e que não me encontro em impedimento para contratar com a Prefeitura do Município de São Paulo / Secretaria Municipal de Cultura, mediante recebimento de cachê e/ou bilheteria, quando for o caso."));

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(167,$l,utf8_decode("Declaro, sob as penas da lei, dentre os integrantes abaixo listados não há crianças e adolescentes. Quando houver, estamos cientes que é de nossa responsabilidade a adoção das providências de obtenção  de  decisão judicial  junto à Vara da Infância e Juventude."));

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(167,$l,utf8_decode("Declaro, ainda, neste ato, que autorizo, a título gratuito, por prazo indeterminado, a Municipalidade de São Paulo, através da SMC, o uso da nossa imagem, voz e performance nas suas publicações em papel e qualquer mídia digital, streaming ou internet existentes ou que venha a existir como também para os fins de arquivo e material de pesquisa e consulta."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(170,$l,utf8_decode("Fico autorizado a celebrar contrato, inclusive receber cachê e/ou bilheteria quando for o caso, outorgando quitação."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(170,$l,utf8_decode("Estou ciente de que o pagamento dos valores decorrentes dos serviços é de minha responsabilidade, não cabendo pleitear à Prefeitura quaisquer valores eventualmente não repassados."));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(170,$l,utf8_decode("Integrantes do grupo: ".$atracao['integrantes']));

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->Cell(180,$l,utf8_decode("São Paulo, _______ / _______ /"."$ano"."."),0,0,'L');

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
    $pdf->Cell(100, 4, utf8_decode($rg), 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');    
}


$pdf->Output();
?>
