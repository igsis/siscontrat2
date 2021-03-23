<?php
$pedido = $eventoObj->recuperaPedido($idEvento);

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180, $l, utf8_decode("PEDIDO DE CONTRATAÇÃO"), 'B', 1, 'C');
$pdf->Ln();


if ($pedido->pessoa_tipo_id == 1){
    $pf = $eventoObj->recuperaPessoaFisica($pedido->pessoa_fisica_id);

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(120, $l, utf8_decode($pf['nome']), 0, 'L', 0);

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(27, $l, utf8_decode("Nome Artístico:"), 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(120, $l, utf8_decode($pf['nome_artistico'] == null ? "Não cadastrado" : $pf['nome_artistico']), 0, 'L', 0);

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);

    if ($pf['passaporte'] != NULL) {
        $pdf->Cell(21, $l, utf8_decode('Passaporte:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(50, $l, utf8_decode($pf['passaporte']), 0, 0, 'L');

    } else {
        $pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(50, $l, utf8_decode($pf['rg'] == NULL ? "Não cadastrado" : $pf['rg']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(45, $l, utf8_decode($pf['cpf']), 0, 0, 'L');
    }

    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(9, $l, utf8_decode("DRT:"), 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->Cell(5, $l, utf8_decode($pf['drt']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(35, $l, 'Data de Nascimento:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->Cell(25, $l, utf8_decode(date("d/m/Y", strtotime($pf['data_nascimento']))), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(26, $l, "Nacionalidade:", 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->Cell(44, $l, utf8_decode($pf['nacionalidade']), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(10, $l, "CCM:", 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->Cell(30, $l, utf8_decode($pf['ccm']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(160, $l, utf8_decode($pf['logradouro'].", ".$pf['numero']." ".$pf['complemento']." - ".$pf['bairro'].", ".$pf['cidade']." - ".$pf['uf'].", ".$pf['cep']), 0, 'L', 0);

    if ($pf['telefones']){
        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(21, $l, 'Telefone(s):', '0', '0', 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->MultiCell(168, $l, utf8_decode($pf['telefones']['tel_0'] ?? null . " " .$pf['telefones']['tel_1'] ?? null. " ".$pf['telefones']['tel_2'] ?? null), 0, 'L', 0);
    }

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(11, $l, 'Email:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(168, $l, utf8_decode($pf['email']), 0, 'L', 0);
}
else {
    $pj = $eventoObj->recuperaPessoaJuridica($pedido->pessoa_juridica_id);
    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(27,$l,utf8_decode('Razão Social:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pj->razao_social),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(14,$l,utf8_decode('CNPJ:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pj->cnpj),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(12,$l,utf8_decode('CCM:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pj->ccm),0,1,'L');
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
