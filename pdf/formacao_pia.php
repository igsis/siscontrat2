<?php
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();

$idFormacao = $_POST['idFormacao'];
$idPedido = $_POST['idPedido'];

$fc = recuperaDados('formacao_contratacoes', 'id', $idFormacao);
$vigencia = $fc['form_vigencia_id'];
$modelo = recuperaDados('juridicos', 'pedido_id', $idPedido);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $fc['pessoa_fisica_id']);
$pedido = $con->query("SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 2 AND publicado = 1")->fetch_array();
$pagamento = $pedido['forma_pagamento'];
$valorT = $pedido['valor_total'];
$valor_extenso = valorPorExtenso($valorT);
$nome = $pessoa['nome'];
$cpf = $pessoa['cpf'];
$amparo = $modelo['amparo_legal'];
$dotacao = $modelo['dotacao'];
$finalizacao = $modelo['finalizacao'];

$carga = null;
$sqlCarga = "SELECT carga_horaria FROM formacao_parcelas WHERE formacao_vigencia_id = " . $fc['form_vigencia_id'];
$queryCarga = mysqli_query($con, $sqlCarga);

while ($countt = mysqli_fetch_array($queryCarga)) {
    $carga += $countt['carga_horaria'];
}

$dataAtual = date("Y/m/d");
$hoje = date("d/m/Y", strtotime("-3 hours"));
$diaSemana = diasemana($dataAtual);

//periodo
$data = retornaPeriodoFormacao_Emia($vigencia, "formacao");

// local
$sqlLocal = "SELECT l.local FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' - ';
}

$local = substr($local, 0, -3);

if ($pessoa['passaporte' != NULL]) {
    $cpf_passaporte = "Passaporte (" . $pessoa['passaporte'] . ")</p>";
} else {
    $cpf_passaporte = "CPF (" . $cpf . ")</p>";
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
    "<p align='justify'>" . "$amparo" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . $nome . ", $cpf_passaporte " .
    "<p><strong>Objeto:</strong> " . retornaObjetoFormacao_Emia($idFormacao, "formacao") . "</p>" .
    "<p><strong>Data / Período:</strong>" . $data . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Locais e Horários</strong></p>" .
    "<p>" . "$local" . "</p>" .
    "<p>" . "$data" . "&nbsp;" . "($diaSemana)" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Carga Horária: </strong>" . $carga . "</p>" .
    "<p><strong> Valor: R$</strong> " . $valorT . " (" . $valor_extenso . " ) </p>" .
    "<p><strong>Forma de Pagamento:</strong> " . $pagamento . "</p>" .
    "<p><strong>Dotação Orçamentária: </strong> " . checaCampo($dotacao) . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . $finalizacao . "</p>" .
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
