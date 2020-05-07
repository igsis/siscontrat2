<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

class PDF extends FPDF
{
// Page header
    function Header()
    {
        // Move to the right

        // Logo
        $this->Image('../pdf/logo_smc.jpg', 30, 10);

        // Line break
        $this->Ln(20);
    }

}

//CONSULTA  (copia inteira em todos os docs)
$idPf = $_GET['idPf'];

$ano = date('Y', strtotime("-3 hours"));
$dataAtual = date("d/m/Y", strtotime("-3 hours"));

$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$nacionalidade = recuperaDados('nacionalidades', 'id', $pessoa['nacionalidade_id'])['nacionalidade'];
$endereco = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);

$testaBanco = $con->query("SELECT b.banco, pf.agencia, b.codigo, pf.conta FROM pf_bancos AS pf INNER JOIN bancos AS b ON b.id = pf.banco_id WHERE pf.publicado = 1 AND pf.pessoa_fisica_id = $idPf");
if ($testaBanco->num_rows > 0) {
    while ($bancoArray = mysqli_fetch_array($testaBanco)) {
        $agencia = $bancoArray['agencia'];
        $conta = $bancoArray['conta'];
        $banco = $bancoArray['banco'];
    }
} else {
    $agencia = "Não cadastrado";
    $conta = "Não cadastrado";
    $banco = "Não cadastrado";
}

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$telefones = mysqli_query($con, $sqlTelefone);
$numTelefone = mysqli_num_rows($telefones);

$nome = $pessoa["nome"];
$nomeArtistico = $pessoa["nome_artistico"];
$dataNascimento = exibirDataBr($pessoa["data_nascimento"]);

if ($pessoa['rg'] != '')
    $documento = $pessoa['rg'];
else
    $documento = $pessoa['passaporte'];

$cpf = $pessoa["cpf"];
$ccm = $pessoa["ccm"];
$rua = $endereco['logradouro'];
$numero = $endereco['numero'];
$bairro = $endereco['bairro'];
$cidade = $endereco['cidade'];
$cep = $endereco['cep'];
$email = $pessoa["email"];

// GERANDO O PDF:
$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 8; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(180, 15, utf8_decode("REGISTRO DE PESSOA FÍSICA"), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode($nome));

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($documento), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, $l, utf8_decode('CPF:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, $l, utf8_decode($cpf), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, $l, utf8_decode('CCM:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(45, $l, utf8_decode($ccm ? "" : "Não cadastrado"), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(36, $l, utf8_decode('Data de Nascimento:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
if ($dataNascimento == "31/12/1969")
    $pdf->Cell(25, $l, " ", 0, 1, 'L');
else
    $pdf->Cell(25, $l, utf8_decode($dataNascimento), 0, 1, 'L');


$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(19, $l, utf8_decode('Endereço:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode($rua . ', ' . $numero . ', ' . $bairro . ' - ' . $cidade));

$count = 1;
foreach ($telefones as $row) {
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, $l, utf8_decode('Telefone ' . $count . ':'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(87, $l, utf8_decode($row['telefone']), 0, 1, 'L');
    $count++;
}

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(13, $l, utf8_decode('E-mail:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(53, $l, utf8_decode($email), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(13, $l, utf8_decode('Banco:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode(str_replace("–", "-", $banco)), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX($x);
$pdf->Cell(16, $l, utf8_decode('Agência:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, $l, utf8_decode($agencia), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX($x);
$pdf->Cell(12, $l, utf8_decode('Conta:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(45, $l, utf8_decode($conta), 0, 1, 'L');
$pdf->Output();
?>