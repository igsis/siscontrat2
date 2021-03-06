<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 2 AND publicado = 1")->fetch_array();
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

$carga = null;
$sqlCarga = "SELECT carga_horaria FROM formacao_parcelas WHERE  publicado = 1 AND formacao_vigencia_id = " . $contratacao['form_vigencia_id'];
$queryCarga = mysqli_query($con, $sqlCarga);

while ($countt = mysqli_fetch_array($queryCarga)) {
    $carga += $countt['carga_horaria'];
}

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

if ($pessoa['passaporte'] != NULL) {
    $cpf_passaporte = "<strong>Passaporte: </strong> " . $pessoa['passaporte'] . "<br />";
} else {
    $cpf_passaporte = "<strong>CPF:</strong> " . $pessoa['cpf'] . "<br />";
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
        "<p align='center'><strong>PEDIDO DE  CONTRATAÇÃO DE PESSOA FÍSICA </strong></p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Sr(a) </strong></p>" .
        "<p>Solicitamos a contratação a seguir:</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Protocolo nº:</strong> " . $contratacao['protocolo'] . "</p>" .
        "<p><strong>Processo SEI nº:</strong> " . checaCampo($pedido['numero_processo']) . "</p>" .
        "<p><strong>Setor  solicitante:</strong> Supervisão de Formação Cultural</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Nome:</strong> " . $pessoa['nome'] . " <br />" .
        $cpf_passaporte .
        "<strong>Telefone:</strong> " . $tel . "<br />" .
        "<strong>E-mail:</strong> " . $pessoa['email'] . "</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Objeto:</strong> " . retornaObjetoFormacao_Emia($idFC,"formacao") . "</p>" .
        "<p><strong>Data / Período:</strong> " . retornaPeriodoFormacao_Emia($contratacao['form_vigencia_id'], "formacao") . " - conforme Proposta/Cronograma</p>" .
        "<p><strong>Carga Horária:</strong> " . $carga . " hora(s)" . "</p>" .
        "<p align='justify'><strong>Local:</strong> " . listaLocaisFormacao($idFC) . "</p>" .
        "<p><strong>Valor: </strong> R$ " . dinheiroParaBr($pedido['valor_total']) . "  (" . valorPorExtenso($pedido['valor_total']) . " )</p>" .
        "<p align='justify'><strong>Forma de Pagamento:</strong> " . $pedido['forma_pagamento'] . "</p>" .
        "<p align='justify'><strong>Justificativa: </strong> " . $pedido['justificativa'] . "</p>" .
        "<p align='justify'>Nos termos do art. 6º do decreto 54.873/2014, fica designado como fiscal desta contratação artística a servidora Natalia Silva Cunha, RF 842.773.9 e, como substituto, Ilton T. Hanashiro Yogi, RF 800.116.2. Diante do exposto, solicitamos autorização para prosseguimento do presente." . "</p>";
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
