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

$ocorrencias = $con->query("SELECT i.nome, i.sigla, o.atracao_id, o.tipo_ocorrencia_id FROM instituicoes AS i INNER JOIN ocorrencias AS o ON o.instituicao_id = i.id WHERE tipo_ocorrencia_id != 3 AND publicado = 1 AND origem_ocorrencia_id =  $idEvento");
$insts = "";

$cargaHoraria = 0;

while ($linhaOco = mysqli_fetch_array($ocorrencias)) {
    $insts = $insts . $linhaOco['nome'] . " (" . $linhaOco['sigla'] . ")" . "; ";

    if ($linhaOco['tipo_ocorrencia_id'] == 1) {
        $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = " . $linhaOco['atracao_id'];
        $carga = $con->query($sqlCarga);
        if ($carga->num_rows > 0) {
            while ($cargaArray = mysqli_fetch_array($carga)) {
                $cargaHoraria = $cargaHoraria + (int)$cargaArray['carga_horaria'];
            }
        }
    }
}

$nome_evento = $evento['nome_evento'];
$pagamento = $pedido['forma_pagamento'];
$valor = $pedido['valor_total'];
$valor_extenso = valorPorExtenso($valor);
$amparo = $modelo['amparo_legal'];
$dotacao = $modelo['dotacao'];

$finalizacao = $modelo['finalizacao'];
$hoje = date("d/m/Y");

if($cargaHoraria == 0){
    $ch = "Não possuí";
}else{
    $ch = $cargaHoraria;
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
    <title>Despacho Formação</title>
</head>

<br>
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
    "<p><strong>Contratado:</strong> " . $y . " - (" . $x . ")</p>" .
    "<p><strong>Objeto:</strong> " . $nome_evento . "</p>" .
    "<p><strong>Data / Período: </strong>" . "$periodo" . "</p>" .
    "<p><strong>Locais:</strong> " . substr($insts, 0, -2) . " </p>" .
    "<p><strong>Carga Horária:</strong> " . $ch . "</p>" .
    "<p><strong> Valor:</strong> R$ " . $valor . "(" . $valor_extenso . " )</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . $pagamento . "</p>" .
    "<p><strong>Dotação Orçamentária: </strong> " . checaCampo($dotacao) . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . $finalizacao . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, " . "$hoje" . "</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="texto" class="texto"><?php echo $dados; ?></div>
</div>
<br>
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

