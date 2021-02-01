<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$ocorrencias = $con->query("SELECT i.nome, o.atracao_id, o.tipo_ocorrencia_id FROM ocorrencias AS o INNER JOIN instituicoes AS i ON o.instituicao_id = i.id WHERE o.tipo_ocorrencia_id != 3 AND o.publicado = 1 AND o.origem_ocorrencia_id =  " . $evento['id']);

$setores = "";
$qtsApresentacao = 0;
$cargaHoraria = 0;

if ($ocorrencias->num_rows > 0) {
    while ($linhaOco = mysqli_fetch_array($ocorrencias)) {
        $setores = $setores . $linhaOco['nome'] . '; ';

        if ($linhaOco['tipo_ocorrencia_id'] == 1) {
            $atracoes = $con->query("SELECT quantidade_apresentacao, nome_atracao, produtor_id FROM atracoes WHERE publicado = 1 AND evento_id = " . $evento['id'] . " AND id = " . $linhaOco['atracao_id']);

            $lista_produtor = "";
            while ($atracao = mysqli_fetch_array($atracoes)) {
                $qtsApresentacao = $qtsApresentacao + (int)$atracao['quantidade_apresentacao'];

                $produtores = $con->query("SELECT * FROM produtores WHERE id = '{$atracao['produtor_id']}'");
                $produtor = mysqli_fetch_array($produtores);
                $lista_produtor = $lista_produtor.
                    $atracao['nome_atracao']."<br> <b>Nome:</b> ".$produtor['nome']."<br> <b>Telefone:</b> ".$produtor['telefone1'] . " | " . $produtor['telefone2'] . "<br> <b>E-mail:</b> ". $produtor['email'];
            }

            $trechoApresentacoes = ", totalizando $qtsApresentacao apresentações conforme proposta/cronograma";

            $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = " . $linhaOco['atracao_id'];
            $carga = $con->query($sqlCarga);

            if ($carga->num_rows > 0) {
                while ($cargaArray = mysqli_fetch_array($carga)) {
                    $cargaHoraria = $cargaHoraria + (int)$cargaArray['carga_horaria'];
                }
            }
        } else if ($linhaOco['tipo_ocorrencia_id'] == 2) {
            $trechoApresentacoes = "";
        }
    }
}else{
    $trechoApresentacoes = "";
}

$setores = substr($setores, 0);

$idEvento = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$evento = recuperaDados('eventos', 'id', $idEvento);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];

$fiscal = recuperaDados('usuarios', 'id', $evento['fiscal_id']);
$suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);

$duracao = "Não se aplica";
if ($evento['tipo_evento_id'] == 2) {
    $duracao = 0;
    $sqlTestaFilme = "SELECT filme_id FROM filme_eventos WHERE evento_id = $idEvento";
    $queryFilme = mysqli_query($con, $sqlTestaFilme);

    while ($idFilmes = mysqli_fetch_array($queryFilme)) {
        $filme = $con->query("SELECT duracao FROM filmes WHERE id = " . $idFilmes['filme_id'])->fetch_array();
        $duracao = $duracao + (int)$filme['duracao'];
    }
    $duracao = $duracao . " Minuto(s)";
}

if ($pedido['numero_processo_mae'] != NULL) {
    $processoMae = "<p><strong>Processo SEI de reserva global:</strong> " . $pedido['numero_processo_mae'] . "</p>";
} else {
    $processoMae = NULL;
}


$numProcesso = $pedido['numero_processo'] == NULL ? "Não cadastrado" : $pedido['numero_processo'];

if ($pessoa['passaporte'] != NULL) {
    $cpf_passaporte = "<strong>Passaporte: </strong> " . $pessoa['passaporte'] . "<br />";
} else {
    $cpf_passaporte = "<strong>CPF:</strong> " . $pessoa['cpf'] . "<br />";
}

if($cargaHoraria == 0){
    $ch = "Não possuí";
}else{
    $ch = $cargaHoraria;
}
?>

<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">

    <style>

        .texto {
            width: 900px;
            border: solid;
            padding: 20px;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: justify;
        }
    </style>
    <link rel="stylesheet" href="../visual/css/bootstrap.min.css">
    <link rel="stylesheet" href="../visual/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="include/dist/ZeroClipboard.min.js"></script>
    <title>Pedido de PF</title>
</head>

<body>
<br>
<div align="center">
    <?php
    $conteudo =
        "<p align='center'><strong>PEDIDO DE  CONTRATAÇÃO DE PESSOA FÍSICA</strong></p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Sr(a) </strong></p>" .
        "<p>Solicitamos a contratação a seguir:</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Protocolo:</strong> " . $evento['protocolo'] . "</p>" .
        "<p><strong>Processo SEI nº:</strong> " . $numProcesso . "</p>" .
        $processoMae .
        "<p><strong>Setor(es)  solicitante(s):</strong> $setores </p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Nome:</strong> " . $pessoa['nome'] . " <br />" .
        $cpf_passaporte .
        "<strong>Telefone(s):</strong> " . $tel . "<br />" .
        "<strong>E-mail:</strong> " . $pessoa['email'] . "</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Produtor:</strong></p>" .
        "<p>$lista_produtor</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Objeto:</strong> " . $objeto . "</p>" .
        "<p><strong>Data / Período:</strong> " . retornaPeriodoNovo($idEvento, 'ocorrencias') . $trechoApresentacoes . "</p>" .
        "<p><strong>Carga Horária:</strong> $ch </p>" .
        "<p><strong>Duração: </strong> $duracao  </p>" .
        "<p align='justify'><strong>Local(ais):</strong> " . listaLocais($idEvento, '1') . "</p>" .
        "<p><strong>Valor: </strong> R$ " . dinheiroParaBr($pedido['valor_total']) . " ( " . valorPorExtenso($pedido['valor_total']) . " )" . "</p>" .
        "<p align='justify'><strong>Forma de Pagamento:</strong> " . $pedido['forma_pagamento'] . "</p>" .
        "<p align='justify'><strong>Justificativa: </strong> " . $pedido['justificativa'] . "</p>" .
        "<p align='justify'>Nos termos do art. 6º do decreto 54.873/2014, fica designado como fiscal desta contratação artística o(a) servidor(a) " . $fiscal['nome_completo'] . ", RF " . $fiscal['rf_rg'] . " e, como substituto, " . $suplente['nome_completo'] . ", RF " . $suplente['rf_rg'] . ". Diante do exposto, solicitamos autorização para prosseguimento do presente." . "</p>";
    ?>

    <div id="texto" class="texto"><?php echo $conteudo; ?></div>
</div>

<p>&nbsp;</p>

<div align="center">
    <button id="botao-copiar" class="btn btn-primary" onclick="copyText(getElementById('texto'))">
        COPIAR TODO O TEXTO
        <i class="fa fa-copy"></i>
    </button>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
        <button class="btn btn-primary">CLIQUE AQUI PARA ACESSAR O <img src="../visual/images/logo_sei.jpg"></button>
    </a>
</div>

<script>
    function copyText(element) {
        var range, selection, worked;

        if (document.body.createTextRange) {
            range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        } else if (window.getSelection) {
            selection = window.getSelection();
            range = document.createRange();
            range.selectNodeContents(element);
            selection.removeAllRanges();
            selection.addRange(range);
        }

        try {
            document.execCommand('copy');
            alert('Copiado com sucesso!');
            selection.removeAllRanges();
        } catch (err) {
            alert('Texto não copiado, tente novamente.');
            selection.removeAllRanges();
        }
    }
</script>

</body>
</html>

