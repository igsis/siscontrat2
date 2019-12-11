<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();


$idParcela = $_POST['idParcela'];
$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$fiscal = recuperaDados('usuarios', 'id', $contratacao['fiscal_id']);
$suplente = recuperaDados('usuarios', 'id', $contratacao['suplente_id']);

$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);


$idVigencia = $contratacao['form_vigencia_id'];

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND id = '$idParcela'";
$query = mysqli_query($con, $sqlParcelas);
while ($parcela = mysqli_fetch_array($query)) {
    if ($parcela['valor'] > 0) {
        $datapgt = exibirDataBr($parcela['data_pagamento']);
    }
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
</head>

<body>
<br>
<div align="center">
    <?php
    $conteudo =
        "<p><strong><u><center>ATESTADO DE CONFIRMAÇÃO DE SERVIÇOS</strong></p></u></center>" .
        "<p>&nbsp;</p>" .
        "<p>Informamos que os serviços prestados por: " . $pessoa['nome'] . "</p>" .
        "<p><strong>PROCESSO: </strong> " . $pedido['numero_processo'] . " </p>" .
        "<p><strong>Programa:</strong> " . $programa['programa'] . " <strong>Linguagem:</strong> " . $linguagem['linguagem'] . " <strong>Edital:</strong> " . $programa['edital'] . "</p>" .
        "<P><strong>PERÍODO: </strong>" . retornaPeriodoFormacao($contratacao['form_vigencia_id']) . "</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS</p>" .
        "<p>( X ) FORAM REALIZADOS A CONTENTO</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS A CONTENTO, PELO SEGUINTE MOTIVO:</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>DADOS DO SERVIDOR (A) QUE ESTÁ CONFIRMANDO OU NÃO A REALIZAÇÃO DOS SERVIÇOS:</strong></p>" .
        "<p><strong>FISCAL:</strong> " . $fiscal['nome_completo'] . "</p>" .
        "<p><strong>RF:</strong> " . $fiscal['rf_rg'] . "</p>" .
        "<p><strong>SUPLENTE:</strong> " . $suplente['nome_completo'] . "</p>" .
        "<p><strong>RF:</strong> " . $suplente['rf_rg'] . "</p>" .
        "<p>&nbsp;</p>" .
        "<p>Atesto que os serviços prestados discriminados no documento: link SEI, foram executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no(s) dia(s): " . $datapgt . ", dentro do prazo previsto.</p>" .
        "<p>O prazo contratual é do dia " . retornaPeriodoFormacao($contratacao['form_vigencia_id']) . ". <p>" .
        "<p>&nbsp;</p>" .
        "<p>À área gestora de liquidação e pagamento encaminho para prosseguimento.</p>"
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



