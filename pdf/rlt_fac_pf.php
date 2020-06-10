<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

// logo da instituição 
session_start(['name' => 'sis']);


class PDF extends FPDF
{
// Page header
    function Header()
    {
        // Logo
        $this->Image('../pdf/fac_pf.jpg', 15, 10, 180);

        // Line break
        $this->Ln(20);
    }

}

//CONSULTA 
if (isset($_POST['idPf'])) {
    $id_Pf = $_POST['idPf'];
} else if (isset($_POST['idPessoa'])) {
    $id_Pf = $_POST['idPessoa'];
}

$ano = date('Y', strtotime("-3 hours"));

$pf = recuperaDados("pessoa_fisicas", "id", $id_Pf);

$testaDrt = $con->query("SELECT drt FROM drts WHERE pessoa_fisica_id = $id_Pf");
if ($testaDrt->num_rows > 0) {
    while ($drtArray = mysqli_fetch_array($testaDrt)) {
        $drt = $drtArray['drt'];
    }
} else {
    $drt = "";
}

$testaNit = $con->query("SELECT nit FROM nits WHERE pessoa_fisica_id = $id_Pf");

if ($testaNit->num_rows > 0) {
    while ($nitArray = mysqli_fetch_array($testaNit)) {
        $nit = $nitArray['nit'];
    }
} else {
    $nit = "";
}

$end = recuperaDados("pf_enderecos", "pessoa_fisica_id", $id_Pf);
$bancos = recuperaDados("pf_bancos", "pessoa_fisica_id", $id_Pf);

$sql_telefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$id_Pf' LIMIT 0,1";
$query = mysqli_query($con, $sql_telefones);
$telefones = mysqli_fetch_array($query);

//endereco
$enderecoArray = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $id_Pf);
if ($enderecoArray == NULL) {
    $rua = "";
    $bairro = "";
    $complemento = "";
    $cidade = "";
    $estado = "";
    $num = "";
    $cep = "";
} else {
    $rua = $enderecoArray['logradouro'];
    $bairro = $enderecoArray['bairro'];
    $complemento = $enderecoArray['complemento'];
    $cidade = $enderecoArray['cidade'];
    $estado = $enderecoArray['uf'];
    $num = $enderecoArray['numero'];
    $cep = $enderecoArray['cep'];
}

//pessoa fisica
$Nome = $pf["nome"];
$RG = $pf["rg"];
if (!empty($pf["cpf"])) {
    $CPF = $pf["cpf"];
} else {
    $CPF = $pf['passaporte'];
}
$CCM = $pf["ccm"];
$Telefone01 = $telefones["telefone"];

$testaBanco = $con->query("SELECT b.banco, pf.agencia, b.codigo, pf.conta FROM pf_bancos AS pf INNER JOIN bancos AS b ON b.id = pf.banco_id WHERE pf.publicado = 1 AND pf.pessoa_fisica_id = $id_Pf");
if ($testaBanco->num_rows > 0) {
    while ($bancoArray = mysqli_fetch_array($testaBanco)) {
        $agencia = $bancoArray['agencia'];
        $conta = $bancoArray['conta'];
        $codbanco = $bancoArray['codigo'];
    }
} else {
    $agencia = "";
    $conta = "";
    $codbanco = "";
}

if ($pf['data_nascimento'] == '0000-00-00') {
    $dataNascimento = "";
} else {
    $dataNascimento = exibirDataBr($pf['data_nascimento']);
}


// GERANDO O PDF:
$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 40);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetXY(113, 40);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, $l, utf8_decode('X'), 0, 0, 'L');

$pdf->SetXY($x, 40);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(53, $l, utf8_decode($CPF), 0, 0, 'L');

$pdf->SetXY(155, 40);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(53, $l, utf8_decode($CCM), 0, 0, 'L');

$pdf->SetXY($x, 55);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(160, $l, utf8_decode($Nome), 0, 0, 'L');

$pdf->SetXY($x, 68);
$pdf->SetFont('Arial', '', 10);

if ($complemento != null) {
    $pdf->Cell(160, $l, utf8_decode("$rua" . ", " . "$num" . " - " . "$complemento"), 0, 0, 'L');
} elseif ($rua != NULL && $complemento == NULL) {
    $pdf->Cell(160, $l, utf8_decode("$rua" . ", " . "$num"), 0, 0, 'L');
}

$pdf->SetXY($x, 82);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(68, $l, utf8_decode($bairro), 0, 0, 'L');
$pdf->Cell(88, $l, utf8_decode($cidade), 0, 0, 'L');
$pdf->Cell(5, $l, utf8_decode($estado), 0, 0, 'L');

$pdf->SetXY($x, 96);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(33, $l, utf8_decode($cep), 0, 0, 'L');
$pdf->Cell(57, $l, utf8_decode($Telefone01), 0, 0, 'L');
$pdf->Cell(15, $l, utf8_decode($codbanco), 0, 0, 'L');
$pdf->Cell(35, $l, utf8_decode($agencia), 0, 0, 'L');
$pdf->Cell(37, $l, utf8_decode($conta), 0, 0, 'L');

$pdf->SetXY($x, 107);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(87, $l, utf8_decode($nit), 0, 0, 'L');
$pdf->Cell(52, $l, utf8_decode($dataNascimento), 0, 0, 'L');
$pdf->Cell(33, $l, utf8_decode(""), 0, 0, 'L');

$pdf->SetXY($x, 122);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(87, $l, utf8_decode($Nome), 0, 0, 'L');
$pdf->Cell(50, $l, utf8_decode($RG), 0, 0, 'L');

$pdf->Output('facc.pdf', 'I');

?>