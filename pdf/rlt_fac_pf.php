<?php 
   
   // INSTALAÇÃO DA CLASSE NA PASTA FPDF.
    require_once("../include/lib/fpdf/fpdf.php");
   require_once("../funcoes/funcoesConecta.php");
   require_once("../funcoes/funcoesGerais.php");

   //CONEXÃO COM BANCO DE DADOS 
   $con = bancoMysqli();
   
// logo da instituição 
session_start();


  
class PDF extends FPDF
{
// Page header
function Header()
{	
    // Logo
    $this->Image('../pdf/fac_pf.jpg',15,10,180);
    
    // Line break
    $this->Ln(20);
}

}

//CONSULTA 
$id_Pf = $_GET['id'];

$ano=date('Y');

$pf = recuperaDados("pessoa_fisicas", "id", $id_Pf);

$drts = recuperaDados("drts", "pessoa_fisica_id", $id_Pf);
$nits = recuperaDados("nits", "pessoa_fisica_id", $id_Pf);
$end = recuperaDados("pf_enderecos", "pessoa_fisica_id", $id_Pf);
$bancos = recuperaDados("pf_bancos", "pessoa_fisica_id", $id_Pf);

$sql_telefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$id_Pf' LIMIT 0,1";
$query = mysqli_query($con, $sql_telefones);
$telefones = mysqli_fetch_array($query);

//endereco
$rua = $end["logradouro"];
$bairro = $end["bairro"];
$cidade = $end["cidade"];
$estado = $end["uf"];
$num = $end['numero'];
$complemento = $end["complemento"];
$cep = $end['cep'];

//pessoa fisica
$Nome = $pf["nome"];
$RG = $pf["rg"];
$CPF = $pf["cpf"];
$CCM = $pf["ccm"];
$Telefone01 = $telefones["telefone"];
$agencia = $bancos["agencia"];
$conta = $bancos["conta"];
$codbanco = $bancos["banco_id"];
$cbo = $bancos["cbo"] ?? NULL;
$nit = $nits["nit"];
$DataNascimento = exibirDataBr($pf["data_nascimento"]);


// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

   
$x=20;
$l=7; //DEFINE A ALTURA DA LINHA   
   
   $pdf->SetXY( $x , 40 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

   $pdf->SetXY(113, 40);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(10,$l,utf8_decode('X'),0,0,'L');

   $pdf->SetXY($x, 40);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(53,$l,utf8_decode($CPF),0,0,'L');
   
   $pdf->SetXY(155, 40);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(53,$l,utf8_decode($CCM),0,0,'L');
   
   $pdf->SetXY($x, 55);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(160,$l,utf8_decode($Nome),0,0,'L');
   
   $pdf->SetXY($x, 68);
   $pdf->SetFont('Arial','', 10);

   if ($complemento != null) {
       $pdf->Cell(160,$l,utf8_decode("$rua".", "."$num"." - "."$complemento"),0,0,'L');
   } else {
       $pdf->Cell(160,$l,utf8_decode("$rua".", "."$num"),0,0,'L');
   }


   
   $pdf->SetXY($x, 82);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(68,$l,utf8_decode($bairro),0,0,'L');
   $pdf->Cell(88,$l,utf8_decode($cidade),0,0,'L');
   $pdf->Cell(5,$l,utf8_decode($estado),0,0,'L');
   
   $pdf->SetXY($x, 96);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(33,$l,utf8_decode($cep),0,0,'L');
   $pdf->Cell(57,$l,utf8_decode($Telefone01),0,0,'L');
   $pdf->Cell(15,$l,utf8_decode($codbanco),0,0,'L');
   $pdf->Cell(35,$l,utf8_decode($agencia),0,0,'L');
   $pdf->Cell(37,$l,utf8_decode($conta),0,0,'L');
   
   $pdf->SetXY($x, 107);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(87,$l,utf8_decode($nit),0,0,'L');
   $pdf->Cell(52,$l,utf8_decode($DataNascimento),0,0,'L');
   $pdf->Cell(33,$l,utf8_decode($cbo),0,0,'L');
   
   $pdf->SetXY($x, 122);
   $pdf->SetFont('Arial','', 9);
   $pdf->Cell(87,$l,utf8_decode($Nome),0,0,'L');
   $pdf->Cell(50,$l,utf8_decode($RG),0,0,'L');

$pdf->Output();


?>