<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();



$idParcela = $_POST['idParcela'];
$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idEC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('emia_contratacao', 'id', $idEC);
$sqlLocal = "SELECT local FROM locais l INNER JOIN emia_contratacao ec on ec.local_id = l.id WHERE ec.id = '$idEC' AND ec.publicado = 1";
$local = $con->query($sqlLocal)->fetch_array();

$testaCarga = $con->query("SELECT carga_horaria FROM emia_parcelas WHERE id = $idParcela");

if($testaCarga->num_rows > 0){
    $horas = mysqli_fetch_array($testaCarga)['carga_horaria'];
}else{
    $horas = "(quantidade de horas não cadastrada)";
}

$cargo = recuperaDados('emia_cargos', 'id', $contratacao['emia_cargo_id']);

$data = date("Y-m-d", strtotime("-3 hours"));
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
        "<p>&nbsp;</p>" .
        "<p><strong>SMC - CONTABILIDADE</strong></p>" .
        "<p><strong>Sr.(a) Contador(a)</strong></p>" .
        "<p>&nbsp;</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Nome:</strong> " . $pessoa['nome'] . "</p>" .
        "<p><strong>CPF:</strong> " . $pessoa['cpf'] . "</p>" .
        "<p><strong>Cargo:</strong> " . $cargo['cargo'] . "</p>" .
        "<p><strong>Local:</strong> " . $local['local'] . "</p>" .
        "<p><strong>Período:</strong> " . retornaPeriodoFormacao_Emia($contratacao['emia_vigencia_id'], "emia") . "</p>" .
        "<p>&nbsp;</p>" .
        "<p align='justify'>Com base na Confirmação de Serviços (Documento SEI link ), atesto que foi efetivamente cumprido " . $horas . " horas de trabalho durante o período supra citado.</p>" .
        "<p align='justify'>Em virtude do detalhamento da Ação em 2019, informamos que o pagamento  no valor de R$ 4.194,72 (quatro mil, cento e noventa e quatro reais e setenta e dois centavos) foi gasto na zona sul de São Paulo, rua Volkswagen, s/nº, Jabaquara, SP.</p>" .
        "<p align='justify'>Encaminhamos o presente para as providências necessárias relativas ao pagamento da parcela do referido processo.</p>" .
        "<p>&nbsp;</p>" .
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





