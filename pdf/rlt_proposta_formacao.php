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
$idFC = $pedido['origem_id'];
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);
$nacionalidade = recuperaDados('nacionalidades', 'id', $pessoa['nacionalidade_id']);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);


$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFC'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

$ano = date('Y');

$idVigencia = $contratacao['form_vigencia_id'];

$carga = null;
$sqlCarga = "SELECT carga_horaria FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia'";
$queryCarga = mysqli_query($con,$sqlCarga);

while ($countt = mysqli_fetch_array($queryCarga))
    $carga += $countt['carga_horaria'];

$sqlDRT = "SELECT drt FROM drts WHERE pessoa_fisica_id = $idPf";
$drt = $con->query($sqlDRT)->fetch_array();
if($drt['drt'] != "" || $drt['drt'] != NULL){
    $drt = $drt['drt'];
}else{
    $drt =  "Não Cadastrado.";
}

if($pessoa['ccm'] != "" || $pessoa['ccm'] != NULL){
    $ccm = $pessoa['ccm'];
}else{
    $ccm = "Não Cadastrado.";
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
$pdf->MultiCell(200, $l, utf8_decode("(Quando se tratar de grupo, o líder do grupo)"), 0, 'L', 0);

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
if($pessoa['passaporte'] != NULL){
    $pdf->Cell(21, $l, utf8_decode('Passaporte:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['passaporte']), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    
}else{
    $pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['rg']), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(45, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');  
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
$pdf->Cell(30, $l, utf8_decode($nacionalidade['nacionalidade']),0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, $l, "CCM:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($ccm),0 ,0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10,$l,'DRT:',0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40,$l,utf8_decode($drt), 0,'L',0);

$endereco = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " .$endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf']), 0, 'L', 0);

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

$pdf->Ln(6);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(13, $l, utf8_decode('Período:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, utf8_decode(retornaPeriodoFormacao($contratacao['form_vigencia_id'])), 0, 0, 'L');

$pdf->Ln(6);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, $l, utf8_decode("Carga Horária:"), '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(168, $l, utf8_decode($carga . " hora(s)"), 0, 0, 'L');

$pdf->Ln(7);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' | ';
}

$local = substr($local, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16, $l, 'Local(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(165, $l, utf8_decode($local), 0, 'L', 0);

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
    $pdf->Cell(100, 4, "RG: " . $pessoa['rg'], 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');    
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
    $pdf->Cell(100, 4, "RG: " . $pessoa['rg'], 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');    
}

$pdf->AddPage('','');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,"CRONOGRAMA",0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,utf8_decode($programa['programa']),0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$idCargo = $contratacao['form_cargo_id'];
$cargo = recuperaDados('formacao_cargos', 'id', $idCargo);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Cargo:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($cargo['cargo']), 0, 'L', 0);


$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, 'Linguagem:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($linguagem['linguagem']), 0, 'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode("O prestador de serviços acima citado é contratado nos termos do Edital " . $programa['edital']
                                                . ", no período " . retornaPeriodoFormacao($idVigencia)
                                                . ", com carga horária total de até: " . $carga
                                                . " hora(s), na forma abaixo descrita:"), 0, 'L', 0);

$pdf->Ln(5);

$idVigencia = $contratacao['form_vigencia_id'];

$sqlParcelas = "SELECT * FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia' ORDER BY data_inicio ASC";
$query = mysqli_query($con,$sqlParcelas);
while($parcela = mysqli_fetch_array($query))
{
    if($parcela['valor'] > 0)
    {
        $inicio = exibirDataBr($parcela['data_inicio']);
        $fim = exibirDataBr($parcela['data_fim']);
        $horas = $parcela['carga_horaria'];

        $pdf->SetX($x);
        $pdf->SetFont('Arial','', 10);
        $pdf->MultiCell(180,$l,utf8_decode("De $inicio a $fim - até $horas hora(s)"));
    }
}

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(180,$l,utf8_decode("São Paulo, ______ de ____________________ de ".$ano).".",0,0,'L');

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
if($pessoa['passaporte'] != NULL){
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
}else{
    $pdf->Cell(100, 4, "RG: " . $pessoa['rg'], 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');    
}

$pdf->Output();
?>

