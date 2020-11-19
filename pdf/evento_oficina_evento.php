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
$modelo_juridico = recuperaDados('juridicos', 'pedido_id', $idPedido);
$pedidos = recuperaDados('pedidos', 'id', $idPedido);
$ocorrencias = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$instituicao = recuperaDados('instituicoes', 'id', $ocorrencias['instituicao_id']);
$finalizacao = $modelo_juridico['finalizacao'];
$amparo = $modelo_juridico['amparo_legal'];
$dotacao = $modelo_juridico['dotacao'];
$hora_inicio = $ocorrencias['horario_inicio'];
$nome_evento = $evento['nome_evento'];
$hoje = date('d/m/Y');
$valor = $pedidos['valor_total'];
$pagamento = $pedidos['forma_pagamento'];
$valor_extenso = valorPorExtenso($valor);
$periodo = retornaPeriodoNovo($idEvento, 'ocorrencias');


if ($pedidos['pessoa_tipo_id'] == 1) {
    $pessoa = recuperaDados("pessoa_fisicas", "id", $pedidos ['pessoa_fisica_id']);
    $y = $pessoa['nome'];
    if ($pessoa['passaporte'] != NULL) {
        $x = $pessoa['passaporte'];
    } else {
        $x = $pessoa['cpf'];
    }

} else if ($pedidos['pessoa_tipo_id'] == 2) {
    $pessoa = recuperaDados('pessoa_juridicas', "id", $pedidos['pessoa_juridica_id']);
    $y = $pessoa['razao_social'];
    $x = $pessoa['cnpj'];
}

$ocorrencias = $con->query("SELECT i.nome, i.sigla, o.data_inicio, o.data_fim, o.domingo, o.segunda, o.terca, o.quarta, o.quinta, o.sexta, o.sabado, o.horario_inicio FROM ocorrencias AS o INNER JOIN instituicoes AS i ON o.instituicao_id = i.id WHERE tipo_ocorrencia_id != 3 AND publicado = 1 AND origem_ocorrencia_id =  $idEvento");
$locais_horarios = "";
$o = 1;
$dias = "";

while ($linhaOco = mysqli_fetch_array($ocorrencias)) {
    if ($linhaOco['data_inicio'] != $linhaOco['data_fim'] && $linhaOco['data_fim'] != "0000-00-00") {
        $periodoOco = "de " . exibirDataBr($linhaOco['data_inicio']) . " à " . exibirDataBr($linhaOco['data_fim']);
    } else {
        $periodoOco = exibirDataBr($linhaOco['data_inicio']);
    }

    $domingo = $linhaOco['domingo'] == 1 ? $dias = "Domingo, " : '';
    $segunda = $linhaOco['segunda'] == 1 ? $dias = "Segunda-Feira, " : '';
    $terca = $linhaOco['terca'] == 1 ? $dias = "Terça-Feria, " : '';
    $quarta = $linhaOco['quarta'] == 1 ? $dias = "Quarta-Feira, " : '';
    $quinta = $linhaOco['quinta'] == 1 ? $dias = "Quinta-Feira, " : '';
    $sexta = $linhaOco['sexta'] == 1 ? $dias = "Sexta-Feira, " : '';
    $sabado = $linhaOco['sabado'] == 1 ? $dias = "Sabádo, " : '';

    $dias = $domingo . $segunda . $terca . $quarta . $quinta . $sexta . $sabado;

    $locais_horarios = $locais_horarios .
        "<p><strong>(Ocorrência #$o):</strong> <br>" .
        $linhaOco['nome'] . " (" . $linhaOco['sigla'] . ")" . "<br>" .
        $periodoOco . " (" . substr($dias, 0,-2) . ")" . " às " . exibirHora($linhaOco['horario_inicio']) . "</p>" .
        "<p>&nbsp;</p>";
    $o++;
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

<br>
<body>
<?php
$dados =
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . $amparo . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . $y . " - (" . $x . ")</p>" .
    "<p><strong>Objeto:</strong> " . $nome_evento . "</p>" .
    "<p><strong>Data / Período:</strong> " . $periodo . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Locais e Horários:</strong> " . "</p>" .

    $locais_horarios .

    "<p><strong> Valor:</strong> R$ " . $valor .  "(" . $valor_extenso . " )</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . $pagamento . ". Os valores devidos aos prestadores de serviços serão apurados mensalmente e pagos a partir do 1º dia útil do mês subsequente da comprovada execução dos serviços, mediante confirmação pela unidade responsável pela fiscalização.</p>" .
    "<p><strong>Dotação Orçamentária:</strong> " . checaCampo($dotacao) . "&nbsp;" . "</p>" .
    "<p>$finalizacao</p>" .
    "<p align='justify'>" . "" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, " . $hoje . "</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="texto" class="texto"><?php echo $dados; ?></div>
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

<script>
    var client = new ZeroClipboard();
    client.clip(document.getElementById("botao-copiar"));
    client.on("aftercopy", function () {
        alert("Copiado com sucesso!");
    });
</script>

</body>
</html>
