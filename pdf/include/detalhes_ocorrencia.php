<?php
$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180, $l, utf8_decode("OCORRÊNCIAS"), 'B', 1, 'C');

$pdf->Ln();

$ocorrencias = $eventoObj->recuperaOcorrencia($idEvento);
foreach ($ocorrencias as $ocorrencia){
    $nomeOrigem = $eventoObj->recuperaOcorrenciaOrigem($ocorrencia->tipo_ocorrencia_id, $ocorrencia->atracao_id);

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', $f);
    $pdf->Cell(180, $l, utf8_decode($nomeOrigem), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(10, $l, utf8_decode("Data:"), 0, 0, 'L');
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(21, $l, date('d/m/Y',strtotime($ocorrencia->data_inicio)), 0, 0, 'L');
    if ($ocorrencia->data_fim != "0000-00-00"){
        $pdf->Cell(27, $l, utf8_decode("até ".date('d/m/Y', strtotime($ocorrencia->data_fim))), 0, 0, 'L');
    }
    $pdf->Cell(45, $l, utf8_decode("das ".$ocorrencia->horario_inicio." às ".$ocorrencia->horario_fim), 0, 0, 'L');
    $pdf->Cell(21,$l,utf8_decode("(".$eventoObj->diadasemanaocorrencia($ocorrencia->id).")"),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(12, $l, utf8_decode("Local:"), 0, 0, 'L');
    $pdf->SetFont('Arial','', $f);
    $pdf->MultiCell(158,$l,utf8_decode("($ocorrencia->sigla) {$ocorrencia->local}"));

    $pdf->SetX($x);
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(170, $l, utf8_decode("Subprefeitura: ".$ocorrencia->subprefeitura), 0, 1, 'L');

    if($ocorrencia->libras == 1 || $ocorrencia->audiodescricao == 1){
        if($ocorrencia->libras == 1){
            $libras = "Libras";
        } else {
            $libras = "";
        }
        if($ocorrencia->audiodescricao == 1){
            $audio = "Audiodescrição";
        } else {
            $audio = "";
        }
        $pdf->SetX($x);
        $pdf->Cell(130, $l, utf8_decode("Especial: ".$libras." ".$audio), 0, 1, 'L');
    }

    $pdf->SetX($x);
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(145, $l, utf8_decode("Retirada de ingresso: ".$ocorrencia->retirada_ingresso), 0, 0, 'L');
    $pdf->Cell(80,$l,utf8_decode("Valor: R$ ".$eventoObj->dinheiroBr($ocorrencia->valor_ingresso)),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','', $f);
    $pdf->Cell(170, $l, utf8_decode("Observação: ".$ocorrencia->observacao), 0, 1, 'L');

    $pdf->Ln();
}


if ($evento->tipo_evento_id == 1){
    $atracoes = $eventoObj->recuperaAtracao($idEvento);
    foreach ($atracoes as $atracao) {


        $excecao = $eventoObj->recuperaOcorrenciaExcecao($atracao->id);
        if ($excecao){
            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', $f);
            $pdf->Cell(26, 6, utf8_decode("EXCEÇÕES em ".$atracao->nome_atracao), 0, 1, 'L');

            $pdf->SetX($x);
            $pdf->SetFont('Arial','', $f);
            $pdf->Cell(180, $l, utf8_decode("Dia(s): ".$excecao), 0, 1, 'L');

            $pdf->Ln();
        }
    }
}

$pdf->Ln(10);