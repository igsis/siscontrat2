<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

class PDF extends FPDF{
}

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, pg.nota_empenho, pg.entrega_nota_empenho, pg.emissao_nota_empenho, e.id AS idEvento, p.numero_processo
FROM pedidos AS p 
INNER JOIN eventos as e ON p.origem_id = e.id
LEFT JOIN pagamentos pg on p.id = pg.pedido_id
WHERE p.publicado = 1 AND e.publicado = 1 AND p.origem_tipo_id = 1 AND p.id = '$idPedido'
")->fetch_assoc();

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

$x=20;
$l=6; //DEFINE A ALTURA DA LINHA   
   
$pdf->SetXY( $x , 45 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(180,5,utf8_decode("RECIBO DE ENTREGA DE NOTA DE EMPENHO"),0,1,'C');

$pdf->Ln();
$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(180,$l,utf8_decode("Recebi, da Secretaria Municipal de Cultura / Contratos Artísticos a:"));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(41,$l,utf8_decode('Nota de Empenho nº:'),0,0,'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(128,$l,utf8_decode($pedido['nota_empenho']),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(24,$l,utf8_decode('Emitida em:'),0,0,'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(60,$l,utf8_decode(exibirDataBr($pedido['emissao_nota_empenho'])),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(50,$l,utf8_decode('Referente ao processo nº:'),0,0,'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(60,$l,utf8_decode($pedido['numero_processo']),0,1,'L');

$pdf->Ln();
$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(180,$l,utf8_decode("São Paulo, ".exibirDataBr($pedido['entrega_nota_empenho'])));

$pdf->Ln();
$pdf->Ln();

if($pedido['pessoa_tipo_id'] == 2) {
    $idPj = $pedido['pessoa_juridica_id'];
    $pj = $con->query("SELECT razao_social, rl1.nome r1_nome, rl1.cpf r1_cpf, rl1.rg r1_rg, rl2.nome r2_nome, rl2.cpf r2_cpf, rl2.rg r2_rg
        FROM pessoa_juridicas pj 
            INNER JOIN representante_legais rl1 on pj.representante_legal1_id = rl1.id 
            LEFT JOIN representante_legais rl2 on pj.representante_legal2_id = rl2.id 
        WHERE pj.id = '$idPj'")->fetch_assoc();

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(170, $l, utf8_decode('Razão Social'), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(170, $l, utf8_decode($pj['razao_social']), 0, 1, 'L');

    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(170, $l, utf8_decode('REPRESENTANTES LEGAIS'), 0, 1, 'L');

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(165, $l, utf8_decode($pj['r1_nome']), 'T', 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(8, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(50, $l, utf8_decode($pj['r1_rg']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(11, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(50, $l, utf8_decode($pj['r1_cpf']), 0, 1, 'L');

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();

    if($pj['r2_nome'] != NULL) {
        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(165, $l, utf8_decode($pj['r2_nome']), 'T', 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(8, $l, utf8_decode('RG:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, $l, utf8_decode($pj['r2_rg']), 0, 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(11, $l, utf8_decode('CPF:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, $l, utf8_decode($pj['r2_cpf']), 0, 1, 'L');
    }
} else{
    $idPf = $pedido['pessoa_fisica_id'];
    $pf = $con->query("SELECT nome, rg, cpf, email FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_assoc();
    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(165,$l,utf8_decode($pf['nome']),'T',1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(8,$l,utf8_decode('RG:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(50,$l,utf8_decode($pf['rg']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(11,$l,utf8_decode('CPF:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(50,$l,utf8_decode($pf['cpf']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(15,$l,utf8_decode('E-mail:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(60,$l,utf8_decode($pf['email']),0,1,'L');
}
$pdf->Ln();
$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(16,$l,'Objeto:',0,0,'L');
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(170,$l,utf8_decode($pedido['nome_evento']));
   
$pdf->Output();