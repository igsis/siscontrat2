<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$idEvento = $pedido['origem_id'];
$idPj = $pedido['pessoa_juridica_id'];
$evento = recuperaDados('eventos', 'id', $idEvento);
$pessoa = recuperaDados('pessoa_juridicas', 'id', $idPj);

$idAtracao = $ocorrencia['atracao_id'];

$atracao = recuperaDados('atracoes', 'id', $idAtracao);

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];

if($evento['tipo_evento_id'] == 2){
    $trechoApre = "exibição de filme";
}else{
    $trechoApre = $atracao['quantidade_apresentacao'] ." (" . qtdApresentacoesPorExtenso($atracao['quantidade_apresentacao']) . " ) apresentações";
}

alteraStatusPedidoContratos($idPedido, "reserva");
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
</head>

<body>
<br>
<div align="center">
    <?php
    $conteudo =
    "<p><strong>Do processo nº:</strong> " . $pedido['numero_processo'] . "</p>".
    "<p>&nbsp;</p>".
    "<p><strong>INTERESSADO:</strong> " . $pessoa['razao_social'] . "</span></p>".
    "<p><strong>ASSUNTO:</strong> " . $objeto . "</p>".
    "<p>&nbsp;</p>".
    "<p><strong>SMC/CAF/SCO</strong></p>".
    "<p><strong>Sr(a). Supervisor</strong></p>".
    "<p>&nbsp;</p>".
    "<p>O presente processo trata da contratação de " . $objeto . ", no valor de R$ " . $pedido['valor_total'] . " ( ".valorPorExtenso($pedido['valor_total'])." ), conforme solicitação LINK, tendo sido anexados os documentos necessários e incluido o parecer técnico LINK, ratificando o caráter artístico e o valor proposto para o cachê referente a ". $trechoApre.", no período ".retornaPeriodoNovo($idEvento, 'ocorrencias') .".</p>".
    "<p>Autorizo a reserva que deverá onerar recursos do FEPAC, dotação 6354 (Pessoa Jurídica), mediante RESERVA COM TRANSFERÊNCIA PARA U.O. 25.10 - Fonte 08.</p>".
    "<p>&nbsp;</p>".
    "<p>&nbsp;</p>".
    "<p>Após, enviar para SMC/AJ para prosseguimento.</p>".
    "<p>&nbsp;</p>"
    ?>

    <div id="texto" class="texto"><?php echo $conteudo; ?></div>
</div>

<p>&nbsp;</p>

<div align="center">
    <button id="botao-copiar" class="btn btn-primary" data-clipboard-target="texto">
        COPIAR TODO O TEXTO
        <i class="fa fa-copy"></i>
    </button>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
        <button class="btn btn-primary">CLIQUE AQUI PARA ACESSAR O <img src="../visual/images/logo_sei.jpg"></button>
    </a>
</div>

<script>
    var client = new ZeroClipboard();
    client.clip(document.getElementById("botao-copiar"));
    client.on("aftercopy", function () {
        alert("Copiado com sucesso!");
    });
</script>

</body>
</html>

