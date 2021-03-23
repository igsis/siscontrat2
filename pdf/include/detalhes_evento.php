<?php
$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(180,5,utf8_decode($evento->nome_evento),0,1,'C');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(19, $l, utf8_decode("Protocolo:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(155, $l, utf8_decode($eventoObj->checaCampo($evento->protocolo)), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(28, $l, utf8_decode("Espaço público:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(15, $l, utf8_decode($eventoObj->simNao($evento->espaco_publico)), 0, 0, 'L');
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(17, $l, utf8_decode("Fomento:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(115, $l, utf8_decode($eventoObj->simNao($evento->fomento) . " " . $evento->fomento_nome), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(30, $l, utf8_decode("Relação jurídica:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(40, $l, utf8_decode($evento->relacao_juridica), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(30, $l, utf8_decode("Projeto especial:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(115, $l, utf8_decode($evento->projeto_especial), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(15, $l, utf8_decode("Público:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->MultiCell(163  , $l, utf8_decode($publicos));

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(17, $l, utf8_decode("Sinopse:"), 0, 1, 'L');
$pdf->SetX($x);
$pdf->SetFont('Arial','', $f);
$pdf->MultiCell(180,$l,utf8_decode($evento->sinopse));

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(53, $l, utf8_decode("Nome do responsável interno:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(75, $l, utf8_decode($evento->nome_responsavel), 0, 0, 'L');
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(17, $l, utf8_decode("Telefone:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(20, $l, utf8_decode($evento->tel_responsavel), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(12, $l, utf8_decode("Fiscal:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(115, $l, utf8_decode($evento->fiscal_nome . " - RF: " . $evento->fiscal_rf), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(17, $l, utf8_decode("Suplente:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(115, $l, utf8_decode($evento->suplente_nome . " - RF: " . $evento->suplente_rf), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', $f);
$pdf->Cell(23, $l, utf8_decode("Inserido por:"), 0, 0, 'L');
$pdf->SetFont('Arial','', $f);
$pdf->Cell(115, $l, utf8_decode($evento->user_nome), 0, 1, 'L');

$pdf->Ln(10);