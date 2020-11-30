<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idEvento = $pedido['origem_id'];
$evento = recuperaDados('eventos', 'id', $idEvento);
$atracao = $con->query("SELECT id, quantidade_apresentacao FROM atracoes WHERE evento_id = '$idEvento'")->fetch_assoc();
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$verba = $con->query("SELECT acao FROM verbas WHERE id = '{$pedido['verba_id']}'")->fetch_assoc();

if ($pedido['pessoa_tipo_id'] == 1) {
    $idPf = $pedido['pessoa_fisica_id'];
    $pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
    $proponente = $pessoa['nome'];
    $tipo = "Pessoa Física";
} else {
    $idPj = $pedido['pessoa_juridica_id'];
    $pessoa = recuperaDados('pessoa_juridicas', 'id', $idPj);
    $proponente = $pessoa['razao_social'];
    $tipo = "Pessoa Jurídica";
}

if($evento['tipo_evento_id'] == 2){
    $trechoApre = "exibição de filme";
}else{
    if ($atracao['quantidade_apresentacao'] > 1){
        $txtApr = "apresentações";
    } else{
        $txtApr = "apresentação";
    }
    $trechoApre = $atracao['quantidade_apresentacao'] ." (" . qtdApresentacoesPorExtenso($atracao['quantidade_apresentacao']) . " ) ".$txtApr;
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
    "<p>&nbsp;</p>".
    "<p><strong>INTERESSADO:</strong> ". $proponente ."  </span></p>".
    "<p><strong>ASSUNTO:</strong> ". $objeto ."  </p>".
    "<p>&nbsp;</p>".
    "<p><strong>SMC/CAF/SCO</strong></p>".
    "<p><strong>Senhor Supervisor</strong></p>".
    "<p>&nbsp;</p>".
    "<p>O presente processo trata da contratação de ". $objeto .", no valor de R$ ". dinheiroParaBr($pedido['valor_total']) ." (".valorPorExtenso($pedido['valor_total']). " ), concernente a " . $trechoApre . ", no período de ". retornaPeriodoNovo($idEvento, 'ocorrencias') .".</p>".
    "<p>Assim, solicito a reserva de recursos que deverá onerar a ação ".$verba['acao']." (".$tipo.") da U.O. 25.10 - Fonte 00. </p>".
    "<p>&nbsp;</p>".
    "<p>Após, enviar para SMC/AJ para prosseguimento.</p>".
    "<p>&nbsp;</p>".
    "<p>Chefe de Gabinete</p>".  
    "<p>&nbsp;</p>"
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

