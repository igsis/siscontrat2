<?php
$con = bancoMysqli();

class PDF extends FPDF{
    function Header()
    {
        // Move to the right

        // Logo
        $this->Image('../pdf/logo_smc.jpg',30, 10);

        // Line break
        $this->Ln(20);
    }
}
$idPf = $_SESSION['idPf'];
$pf = recuperaDados('pessoa_fisicas', 'id', $idPf);

$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x=20;
$l=8; //DEFINE A ALTURA DA LINHA

$pdf->SetXY( $x , 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(180,15,utf8_decode("PEDIDO DE CONTRATAÇÃO DE PESSOA FÍSICA"),0,1,'C');

$pdf->Ln(5);

//$pdf->Image($fotoImg,160,56, );
$pdf->SetX(160);


$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,'Nome:',0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(168,$l,utf8_decode($pf['nome']));

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(10,$l,utf8_decode('CPF:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(40,$l,utf8_decode($cpf),0,0,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,'Email:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(168,$l,utf8_decode($pf['email']));

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12,$l,'Telefone:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell('');


$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(36,$l,utf8_decode('Data de Nascimento:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
if($dataNascimento == "31/12/1969")
    $pdf->Cell(25,$l, " " ,0,1,'L');
else
    $pdf->Cell(25,$l,utf8_decode($pf['data_nascimento']),0,1,'L');

$pdf->Output();
?>