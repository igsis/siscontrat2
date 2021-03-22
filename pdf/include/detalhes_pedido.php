<?php
$pedido = $eventoObj->recuperaPedido($idEvento);
$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180, $l, utf8_decode("PEDIDO DE CONTRATAÇÃO"), 'B', 1, 'C');
$pdf->Ln();

if ($pedido->pessoa_tipo_id == 1){
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(14, $l, utf8_decode('Nome:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pedido->nome), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(9, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pedido->rg), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(10, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pedido->cpf), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(12, $l, utf8_decode('CCM:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pedido->ccm), 0, 1, 'L');
}
else {
    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(27,$l,utf8_decode('Razão Social:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pedido->razao_social),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(14,$l,utf8_decode('CNPJ:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pedido->cnpj),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(12,$l,utf8_decode('CCM:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pedido->ccm),0,1,'L');
    /*
            $pdf->SetX($x);
            $pdf->SetFont('Arial','B', 11);
            $pdf->Cell(37,$l,utf8_decode('Responsável (eis):'),0,0,'L');
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(143,$l,utf8_decode($rep1['nome']),0,1,'L');

            $pdf->SetX($x);
            $pdf->SetFont('Arial','B', 11);
            $pdf->Cell(37,$l,'',0,0,'L');
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(143,$l,utf8_decode($rep2['nome']),0,1,'L');*/
}