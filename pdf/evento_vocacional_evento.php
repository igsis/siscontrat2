<?php
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
isset($_POST['idEvento']);
$idEvento = $_POST['idEvento'];

$pessoa = recuperaDados('pessoa_fisicas','id',$idEvento);
$modelo = recuperaDados('juridicos','pedido_id',$idEvento);
$pedido = recuperaDados('pedidos','origem_id',$idEvento);
$periodo = retornaPeriodoNovo($idEvento, 'ocorrencias');
$evento = recuperaDados('eventos','id',$idEvento);
$atracao = recuperaDados('atracoes','evento_id',$evento['id']);
$ocorrencias = recuperaDados('ocorrencias','atracao_id',$atracao['id']);
$instituicao = recuperaDados('instituicoes', 'id', $ocorrencias['instituicao_id']);
$nome_instituicao = $instituicao['nome'];
$sigla = $instituicao['sigla'];
$nome_evento = $evento['nome_evento'];
$pagamento = $pedido['forma_pagamento'];
$valor = $pedido['valor_total'];
$valor_extenso = valorPorExtenso($valor);
$amparo = $modelo['amparo_legal'];
$dotacao = $modelo['dotacao'];
$finalizacao = $modelo['finalizacao'];
$data = date("Y/m/d");
$hoje = date("d/m/Y");

?>

<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">

    <style>

        .texto{
            width: 900px;
            border: solid;
            padding: 20px;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align:justify;
        }
    </style>
</head>




<body>
<?php
if ($pedido['pessoa_tipo_id'] == 1) {
    $pessoa = recuperaDados("pessoa_fisicas", "id", $pedido ['pessoa_fisica_id']);
    $y = $pessoa['nome'];
    $x = $pessoa['cpf'];
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
    "<p><strong>Data / Período:</strong>"."$periodo"."</p>" .
    "<p><strong>Locais:</strong> " ." $nome_instituicao ".""."($sigla)"." </p>" .
    "<p><strong>Carga Horária:</strong><p>".""."</p>".
    "<p><strong> Valor:</strong> " . "R$ " . " $valor " . "($valor_extenso)" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "$pagamento" . "</p>" .
    "<p><strong>Dotação Orçamentária: </strong> " . " $dotacao"."</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$finalizacao" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>".
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, "."$hoje"."</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="dados" class="texto"><?php echo $dados; ?></div>
</div>

</body>
</html>

