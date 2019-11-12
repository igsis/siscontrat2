<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

class PDF extends FPDF
{
    // Simple table
    function ImprovedTable($header,$line)
    {
        // Column widths
        $w = array(35, 50, 50, 35);
        // Header
        for ($i = 0; $i < count($header); $i++){
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C');
        }

        $this->Ln();

        $this->SetX(20);

        // Line
        for($a = 0; $a <= 30 ; $a++){
            for ($i = 0; $i < count($line); $i++){
                $this->Cell($w[$i], 6, $line[$i], 1, 0, 'C');
            }
            $this->Ln();
            $this->SetX(20);
        }

        //last line
        $this->SetX(20);
        $this->Cell(135,6,"TOTAL HORAS",1,0,'L');
        $this->Cell(35,6," ",1,0,'L');
    }
}

$header = array('DIA', 'CARGA HORÁRIA', 'TURMA / ATIVIDADE', 'ASSINATURA');
$line = array('','','','');
$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idEC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('emia_contratacao', 'id', $idEC);
$idCargo = $contratacao['emia_cargo_id'];
$sqlCargo = "SELECT cargo FROM emia_cargos WHERE id = '$idCargo'";
$cargo = $con->query($sqlCargo)->fetch_array();

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(180, 15, utf8_decode("PREFEITURA  MUNICIPAL DE SÃO PAULO"), 0, 1, 'C');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 11);
$pdf->MultiCell(160, $l, utf8_decode("SECRETARIA MUNICIPAL DE CULTURA - ESCOLA MUNICIPAL DE INICIAÇÃO ARTÍSTICA"), 0, 'C', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->MultiCell(165, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode($cargo['cargo'] . " - de segunda-feira a sábado, em dias e horários determinados pela direção da Escola."), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 12);
$pdf->MultiCell(170, $l, utf8_decode("FOLHA DE FREQUÊNCIA INDIVIDUAL (carga de aula)"), 1, 'C', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->ImprovedTable($header,$line);

$pdf->SetXY(65,40);
$pdf->MultiCell(75,$l,utf8_decode("Diretor"),0,'C',0);

$pdf->SetXY(65,45);
$pdf->MultiCell(75,$l,utf8_decode("RF: 840.968-4"),0,'C',0);

$pdf->SetXY(65,60);
$pdf->MultiCell(75,$l,utf8_decode("Antonio Francisco da Silva Junior"),'T','C',0);


$pdf->Output();
?>



