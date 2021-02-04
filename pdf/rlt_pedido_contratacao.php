<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = $con->query("SELECT id, protocolo, tipo_evento_id, nome_evento, fiscal_id, suplente_id FROM eventos WHERE id = '{$pedido['origem_id']}'")->fetch_assoc();

if ($pedido['pessoa_tipo_id'] == 2) {
    $tipo = "JURÍDICA";
    $pessoa = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
    $proponente = "<strong>Razão Social:</strong> ".$pessoa['razao_social'];
    $documento ="<strong>CNPJ:</strong> ". $pessoa['cnpj'];
    $email = $pessoa['email'];
    $sqlTelefone = $con->query("SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '{$pessoa['id']}' AND publicado = 1");
    $tel = "";
    while ($linhaTel = mysqli_fetch_array($sqlTelefone)) {
        $tel = $tel . $linhaTel['telefone'] . ' | ';
    }
    $tel = substr($tel, 0, -3);
} else {
    $tipo = "FÍSICA";
    $pessoa = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
    $proponente = "<strong>Nome:</strong> ".$pessoa['nome'];
    $documento = "<strong>CPF:</strong> ".$pessoa['cpf'];
    $email = $pessoa['email'];
    $sqlTelefone = $con->query("SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '{$pessoa['id']}' AND publicado = 1");
    $tel = "";
    while ($linhaTel = mysqli_fetch_array($sqlTelefone)) {
        $tel = $tel . $linhaTel['telefone'] . ' | ';
    }
    $tel = substr($tel, 0, -3);
}

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$fiscal = $con->query("SELECT nome_completo, rf_rg FROM usuarios WHERE id = '{$evento['fiscal_id']}'")->fetch_assoc();
$suplente = $con->query("SELECT nome_completo, rf_rg FROM usuarios WHERE id = '{$evento['suplente_id']}'")->fetch_assoc();
$totalApresentacao = $con->query("SELECT SUM(quantidade_apresentacao) as apresentacoes FROM atracoes WHERE publicado = 1 AND evento_id = '{$evento['id']}'")->fetch_row()[0];
?>

<html lang="PT">
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
    <title>Pedido de Contratação</title>
</head>

<body>
<br>
<div align="center">
    <div id="texto" class="texto">
        <h6 class="text-center"><strong>PEDIDO DE  CONTRATAÇÃO DE PESSOA <?= $tipo ?></strong></h6>
        <p>&nbsp;</p>
        <p><strong>Sr(a).</strong></p>
        <p>Solicitamos a contratação a seguir:</p>
        <p>&nbsp;</p>
        <p><strong>Protocolo:</strong> <?= $evento['protocolo'] ?></p>
        <p><strong>Processo SEI nº:</strong> <?= $pedido['numero_processo'] ?></p>
        <p><strong>Processo SEI de reserva global:</strong> <?= $pedido['numero_processo_mae'] ?></p>
        <p><strong>Setor  solicitante:</strong> <?= instituicaoSolicitante($evento['id']) ?> </p>
        <p>&nbsp;</p>
        <p class="text-left"><?= $proponente ?> <br>
            <?= $documento ?><br>
            <strong>Telefone(s):</strong> <?= $tel ?> <br>
            <strong>E-mail:</strong> <?= $email ?> </p>
        <p>&nbsp;</p>
        <p><strong>Produtor:</strong></p>
        <?php
        $atracoes = $con->query("SELECT nome_atracao, produtor_id FROM atracoes WHERE publicado = 1 AND evento_id = '{$evento['id']}'")->fetch_all(MYSQLI_ASSOC);
        foreach ($atracoes as $atracao){
            $produtor= $con->query("SELECT * FROM produtores WHERE id = '{$atracao['produtor_id']}'")->fetch_array();
            ?>
            <p class="text-left">
                <b><?= $atracao['nome_atracao'] ?></b><br>
                <b>Nome:</b> <?= $produtor['nome'] ?> <br>
                <b>Telefone:</b> <?= $produtor['telefone1'] ?> <?= $produtor['telefone2'] ? " / ".$produtor['telefone2'] : null ?> <br>
                <b>E-mail:</b> <?= $produtor['email'] ?>
            </p>
        <?php
        }
        ?>
        <p>&nbsp;</p>
        <p><strong>Objeto:</strong> <?= $objeto ?>.</p>

        <p><strong>Data / Período:</strong> <?= retornaPeriodoNovo($evento['id'], 'ocorrencias') ?>, totalizando <?= $totalApresentacao ?> apresentações conforme proposta/cronograma.</p>
        <p class="text-justify"><strong>Local(ais):</strong> <?= listaLocais($evento['id'], '1') ?>.</p>
        <p><strong>Valor: </strong> R$ <?= dinheiroParaBr($pedido['valor_total']) . " ( " .valorPorExtenso($pedido['valor_total']) . ")" ?>.</p>
        <p class="text-justify"><strong>Forma de pagamento:</strong> <?=  $pedido['forma_pagamento'] ?></p>
        <p class="text-justify"><strong>Justificativa:</strong> <?= $pedido['justificativa'] ?></p>
        <p class="text-justify">Nos termos do art. 6º do decreto 54.873/2014, fica designado como fiscal desta contratação artística o(a) servidor(a) <?= $fiscal['nome_completo'] . ", RF " . $fiscal['rf_rg'] . " e, como substituto, " . $suplente['nome_completo'] . ", RF " . $suplente['rf_rg']  ?>. Diante do exposto, solicitamos autorização para prosseguimento do presente.</p>
    </div>
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