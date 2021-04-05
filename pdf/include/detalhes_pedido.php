<?php
$pedido = $eventoObj->recuperaPedido($idEvento);
$anexos = $eventoObj->recuperaArquivoPedido($pedido->id);
$valoresPorEquipamento = $eventoObj->recuperaValorLocal($pedido->id);
$parecer = $eventoObj->recuperaParecer($pedido->id);
$dataEtapas = $eventoObj->recuperaPedidoEtapas($pedido->id);

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

    if ($pf['banco']){
        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(13, $l, utf8_decode("Banco:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(67, $l, utf8_decode($pf['banco']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(16, $l, utf8_decode("Agência:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(44, $l, utf8_decode($pf['agencia']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(12, $l, utf8_decode("Conta:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(20, $l, utf8_decode($pf['conta']), 0, 1, 'L');
    }
}
else {
    $pj = $eventoObj->recuperaPessoaJuridica($pedido->pessoa_juridica_id);

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', $f);
    $pdf->Cell(24,$l,utf8_decode('Razão Social:'),0,0,'L');
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(150,$l,utf8_decode($pj['razao_social']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', $f);
    $pdf->Cell(12,$l,utf8_decode('CNPJ:'),0,0,'L');
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(150,$l,utf8_decode($pj['cnpj']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', $f);
    $pdf->Cell(10,$l,utf8_decode('CCM:'),0,0,'L');
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(150,$l,utf8_decode($pj['ccm']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(160, $l, utf8_decode($pj['logradouro'].", ".$pj['numero']." ".$pj['complemento']." - ".$pj['bairro'].", ".$pj['cidade']." - ".$pj['uf'].", ".$pj['cep']), 0, 'L', 0);

    if ($pj['telefones']){
        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(21, $l, 'Telefone(s):', '0', '0', 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->MultiCell(168, $l, utf8_decode($pj['telefones']['tel_0'] ?? null . " " .$pj['telefones']['tel_1'] ?? null. " ".$pj['telefones']['tel_2'] ?? null), 0, 'L', 0);
    }

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(11, $l, 'Email:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(168, $l, utf8_decode($pj['email']), 0, 'L', 0);

    if ($pj['banco']){
        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(13, $l, utf8_decode("Banco:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(67, $l, utf8_decode($pj['banco']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(16, $l, utf8_decode("Agência:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(44, $l, utf8_decode($pj['agencia']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', $f);
        $pdf->Cell(12, $l, utf8_decode("Conta:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(20, $l, utf8_decode($pj['conta']), 0, 1, 'L');
    }
/*
            $pdf->SetX($x);
            $pdf->SetFont('Arial','B', $f);
            $pdf->Cell(37,$l,utf8_decode('Responsável (eis):'),0,0,'L');
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(143,$l,utf8_decode($rep1['nome']),0,1,'L');

            $pdf->SetX($x);
            $pdf->SetFont('Arial','B', 11);
            $pdf->Cell(37,$l,'',0,0,'L');
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(143,$l,utf8_decode($rep2['nome']),0,1,'L');*/
}

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', $f);
$pdf->Cell(11, $l, 'Valor:', '0', '0', 'L');
$pdf->SetFont('Arial', '', $f);
$pdf->MultiCell(168, $l, utf8_decode("R$ " . $eventoObj->dinheiroBr($pedido->valor_total) . " (" .  $eventoObj->valorPorExtenso($pedido->valor_total) . " )"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', $f);
$pdf->Cell(12, $l, utf8_decode('Verba:'), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(150,$l,utf8_decode($pedido->verba),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', $f);
$pdf->Cell(38, $l, 'Forma de Pagamento:', 0, 1, 'L');
$pdf->SetX($x);
$pdf->SetFont('Arial', '', $f);
$pdf->MultiCell(180, $l, utf8_decode($pedido->forma_pagamento));

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', $f);
$pdf->Cell(22, $l, utf8_decode('Justificativa:'), '0', '1', 'L');
$pdf->SetX($x);
$pdf->SetFont('Arial', '', $f);
$pdf->MultiCell(180, $l, utf8_decode($pedido->justificativa));

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', $f);
$pdf->Cell(22, $l, utf8_decode('Observação:'), '0', '1', 'L');
$pdf->SetX($x);
$pdf->SetFont('Arial', '', $f);
$pdf->MultiCell(180, $l, utf8_decode($pedido->observacao));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', $f);
$pdf->Cell(11, $l,  utf8_decode('Arquivos do Pedido:'), '0', '1', 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B',$f);
$pdf->Cell(90, 8,  utf8_decode('Tipo de arquivo'), '1', '0', 'L');
$pdf->Cell(50, 8,  utf8_decode('Nome do documento'), '1', '0', 'L');
$pdf->Cell(40, 8,  utf8_decode('Data de envio'), '1', '1', 'L');

foreach ($anexos as $anexo){
    $pdf->SetX($x);
    $pdf->SetFont('Arial','',$f);
    $pdf->Cell(90, 8,  utf8_decode(mb_strimwidth($anexo->documento,0,50,'...')), '1', '0', 'L');
    $pdf->SetFont('Arial','U',$f);
    $pdf->Cell(50, 8,  utf8_decode(mb_strimwidth($anexo->arquivo,15,25,'...')), '1', '0', 'L',false,"../uploadsdocs/" . $anexo->arquivo);
    $pdf->SetFont('Arial','',$f);
    $pdf->Cell(40, 8,  date('d/m/Y H:i:s', strtotime($anexo->data)), '1', '0', 'L');
    $pdf->Ln();
}

$pdf->Ln();

if ($valoresPorEquipamento){
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(11, $l,  utf8_decode('Valores por Equipamento:'), '0', '1', 'L');
   foreach ($valoresPorEquipamento as $valor){
        $pdf->SetX($x);
        $pdf->SetFont('Arial', '', $f);
        $pdf->MultiCell(168, $l, utf8_decode($valor->local . ": R$ " .  $eventoObj->dinheiroBr($valor->valor)), 0, 'L', 0);
   }
} else{
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(180, $l, utf8_decode('Valores por equipamento não cadastrados'), '0', '1', 'L');
}

$pdf->Ln();

if ($parecer){
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(11, $l,  utf8_decode('Parecer Artístico:'), '0', '1', 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(22, $l, utf8_decode('1º Tópico:'), '0', '1', 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(180, $l, utf8_decode($parecer->topico1));

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(22, $l, utf8_decode('2º Tópico:'), '0', '1', 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(180, $l, utf8_decode($parecer->topico2 ?? null));

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(22, $l, utf8_decode('3º Tópico:'), '0', '1', 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(180, $l, utf8_decode($parecer->topico3 ?? null));

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(22, $l, utf8_decode('4º Tópico:'), '0', '1', 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', $f);
    $pdf->MultiCell(180, $l, utf8_decode($parecer->topico4));
}

$pdf->Ln();

// Data das etapas
if ($dataEtapas){
    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', $f);
    $pdf->Cell(180, $l,  utf8_decode('Data de recebimento pelos setores:'), '0', '1', 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B',$f);
    $pdf->Cell(30, $l,  utf8_decode('Contrato'), '1', '0', 'L');
    $pdf->Cell(30, $l,  utf8_decode('Proposta'), '1', '0', 'L');
    $pdf->Cell(30, $l,  utf8_decode('Reserva'), '1', '0', 'L');
    $pdf->Cell(30, $l,  utf8_decode('Jurídico'), '1', '0', 'L');
    $pdf->Cell(30, $l,  utf8_decode('Contabilidade'), '1', '0', 'L');
    $pdf->Cell(30, $l,  utf8_decode('Pagamento'), '1', '1', 'L');

    $f = 8;
    foreach ($dataEtapas as $data){
        $pdf->SetX($x);
        $pdf->SetFont('Arial', '', $f);
        $pdf->Cell(30, $l, utf8_decode($eventoObj->validaData($data->data_contrato,true)), '1', '0', 'L');
        $pdf->Cell(30, $l, utf8_decode($eventoObj->validaData($data->data_proposta,true)), '1', '0', 'L');
        $pdf->Cell(30, $l, utf8_decode($eventoObj->validaData($data->data_reserva,true)), '1', '0', 'L');
        $pdf->Cell(30, $l, utf8_decode($eventoObj->validaData($data->data_juridico,true)), '1', '0', 'L');
        $pdf->Cell(30, $l, utf8_decode($eventoObj->validaData($data->data_contabilidade,true)), '1', '0', 'L');
        $pdf->Cell(30, $l, utf8_decode($eventoObj->validaData($data->data_pagamento,true)), '1', '0', 'L');
        $pdf->Ln();
    }
}
