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
        $this->Image('../pdf/fac_pj.jpg', 15, 10, 180);

        // Line break
        $this->Ln(20);
    }

}

//CONSULTA
if (isset($_GET['id'])) {
    $id_Pj = $_GET['id'];
} else if (isset($_POST['idPessoa'])) {
    $id_Pj = $_POST['idPessoa'];
}

//$idEvento = $_SESSION['idEvento'];

$ano = date('Y', strtotime("-3 hours"));

$pj = recuperaDados("pessoa_juridicas", "id", $id_Pj);
$rep01 = recuperaDados("representante_legais", "id", $pj['representante_legal1_id']);

isset($pj['representante_legal2_id']) ??
$rep02 = recuperaDados("representante_legais", "id", $pj['representante_legal2_id']);

$sql_telefones = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$id_Pj' LIMIT 0,1";
$query = mysqli_query($con, $sql_telefones);
$telefones = mysqli_fetch_array($query);

$end = recuperaDados("pj_enderecos", "pessoa_juridica_id", $id_Pj);
$bancos = recuperaDados("pj_bancos", "pessoa_juridica_id", $id_Pj);

//endereço
$rua = $end["logradouro"];
$bairro = $end["bairro"];
$cidade = $end["cidade"];
$estado = $end["uf"];
$num = $end['numero'];
$pjComplemento = $end["complemento"];
$cep = $end['cep'];


//PessoaJuridica
$pjRazaoSocial = $pj["razao_social"];
$pjCNPJ = $pj['cnpj'];
$pjCCM = $pj["ccm"];
$pjTelefone01 = $telefones["telefone"];
$testaBanco = $con->query("SELECT b.banco, pj.agencia, pj.conta, b.codigo FROM pj_bancos AS pj INNER JOIN bancos AS b ON b.id = pj.banco_id WHERE pj.publicado = 1 AND pj.pessoa_juridica_id = $id_Pj");
if ($testaBanco->num_rows > 0) {
    while ($bancoArray = mysqli_fetch_array($testaBanco)) {
        $agencia = $bancoArray['agencia'];
        $conta = $bancoArray['conta'];
        $codigo = $bancoArray['codigo'];
    }
} else {
    $agencia = "";
    $conta = "";
    $codigo = "";
}


// Representante01
$rep01Nome = $rep01["nome"];
$rep01RG = $rep01["rg"];
$rep01CPF = $rep01["cpf"];


// GERANDO O PDF:
$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 40);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetTitle("FACC PJ");

$pdf->SetXY(112, 43);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, $l, utf8_decode('X'), 0, 0, 'L');

$pdf->SetXY($x, 45);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(53, $l, utf8_decode($pjCNPJ), 0, 0, 'L');

$pdf->SetXY(150, 43);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(53, $l, utf8_decode($pjCCM), 0, 0, 'L');

$pdf->SetXY($x, 60);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(160, $l, utf8_decode($pjRazaoSocial), 0, 0, 'L');

$pdf->SetXY($x, 75);
$pdf->SetFont('Arial', '', 10);

if ($pjComplemento != null) {
    $pdf->Cell(160, $l, utf8_decode("$rua" . ", " . "$num" . " - " . "$pjComplemento"), 0, 0, 'L');
} else {
    $pdf->Cell(160, $l, utf8_decode("$rua" . ", " . "$num"), 0, 0, 'L');
}

$pdf->SetXY($x, 90);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(65, $l, utf8_decode($bairro), 0, 0, 'L');
$pdf->Cell(83, $l, utf8_decode($cidade), 0, 0, 'L');
$pdf->Cell(5, $l, utf8_decode($estado), 0, 0, 'L');

$pdf->SetXY($x, 105);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(33, $l, utf8_decode($cep), 0, 0, 'L');
$pdf->Cell(45, $l, utf8_decode($pjTelefone01), 0, 0, 'L');

$pdf->SetXY(98, 107);
$pdf->Cell(15, $l, utf8_decode($codigo), 0, 0, 'L');
$pdf->Cell(40, $l, utf8_decode($agencia), 0, 0, 'L');
$pdf->Cell(37, $l, utf8_decode($conta), 0, 0, 'L');

$pdf->SetXY($x, 127);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(80, $l, utf8_decode($rep01Nome), 0, 0, 'L');
$pdf->Cell(50, $l, utf8_decode($rep01RG), 0, 0, 'L');

$pdf->Output();


?>