<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$idEvento = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];

$ano = date('Y');

$parecer = recuperaDados("parecer_artisticos", "pedido_id", $idPedido);

if ($parecer) {
    $parecer = nl2br($parecer['topico1']);
} else {
    $parecer = "(Parecer não cadastrado)";
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
        "<p align='right'>Folha de Informação nº ___________</p>" .
        "<p><strong>Do processo nº:</strong> " . $pedido['numero_processo'] . "</p>" .
        "<p align='right' class='style_01'>Data: _______ / _______ / " . $ano . ".  </p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>INTERESSADO:</strong> " . $pessoa['nome'] . "  </span></p>" .
        "<p><strong>ASSUNTO:</strong> " . $objeto . "  </p>" .
        "<p>&nbsp;</p>" .
        "<p>&nbsp;</p>" .
        "<p align='center'><strong>PARECER DA COMISSÃO TÉCNICA DE ATIVIDADES ARTÍSTICAS E CULTURAIS<br/> 
							(Instituído pela Portaria nº 168/2019-SMC-G e nº 050/2019-SMC.G)</strong></p>" .
        "<p>&nbsp;</p>" .

        "<p align='justify'>" . $parecer ."</p>" .


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

