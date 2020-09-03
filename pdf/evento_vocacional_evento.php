<?php
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
isset($_POST['idEvento']);
$idEvento = $_POST['idEvento'];
isset($_POST['idPedido']);
$idPedido = $_POST['idPedido'];

$evento = recuperaDados('eventos', 'id', $idEvento);
$modelo = recuperaDados('juridicos', 'pedido_id', $idPedido);
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$periodo = retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias');
$ocorrencias = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$instituicao = recuperaDados('instituicoes', 'id', $ocorrencias['instituicao_id']);


$nome_instituicao = $instituicao['nome'];
$sigla = $instituicao['sigla'];
$nome_evento = $evento['nome_evento'];
$pagamento = $pedido['forma_pagamento'];
$valor = $pedido['valor_total'];
$valor_extenso = valorPorExtenso($valor);
$amparo = $modelo['amparo_legal'];
$dotacao = $modelo['dotacao'];
if ($dotacao == '') {
    $dotacao = "Não cadastrado";
}
$finalizacao = $modelo['finalizacao'];
$hoje = date("d/m/Y");

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
<?php
if ($pedido['pessoa_tipo_id'] == 1) {
    $pessoa = recuperaDados("pessoa_fisicas", "id", $pedido ['pessoa_fisica_id']);
    $y = $pessoa['nome'];
    if ($pessoa['passaporte'] != NULL) {
        $x = $pessoa['passaporte'];
    } else {
        $x = $pessoa['cpf'];
    }

} else if ($pedido['pessoa_tipo_id'] == 2) {
    $pessoa = recuperaDados('pessoa_juridicas', "id", $pedido['pessoa_juridica_id']);
    $y = $pessoa['razao_social'];
    $x = $pessoa['cnpj'];
}
$dados =
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$amparo" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . "$y" . " - (" . "$x" . ")</p>" .
    "<p><strong>Objeto:</strong> " . "$nome_evento" . "</p>" .
    "<p><strong>Data / Período:</strong>" . "$periodo" . "</p>" .
    "<p><strong>Locais:</strong> " . " $nome_instituicao " . "" . "($sigla)" . " </p>" .
    "<p><strong>Carga Horária:</strong><p>" . "" . "</p>" .
    "<p><strong> Valor:</strong> " . "R$ " . " $valor " . "($valor_extenso)" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "$pagamento" . "</p>" .
    "<p><strong>Dotação Orçamentária: </strong> " . " $dotacao" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$finalizacao" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, " . "$hoje" . "</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="dados" class="texto"><?php echo $dados; ?></div>
</div>
<br>
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
</body>
</html>

