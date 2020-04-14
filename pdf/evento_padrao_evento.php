<?php

require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
isset($_POST['idEvento']);
$idEvento = $_POST['idEvento'];


$evento = recuperaDados('eventos','id',$idEvento);
$modelo_juridico = recuperaDados('juridicos','pedido_id',$idEvento);
$pedidos = recuperaDados('pedidos','origem_id',$idEvento);
$ocorrencias = recuperaDados('ocorrencias','id',$idEvento);
$instituicao = recuperaDados('instituicoes', 'id', $ocorrencias['instituicao_id']);
$finalizacao = $modelo_juridico['finalizacao'];
$amparo = $modelo_juridico['amparo_legal'];
$dotacao = $modelo_juridico['dotacao'];
$hora_inicio = $ocorrencias['horario_inicio'];
$nome_evento = $evento['nome_evento'];
$nome_instituicao = $instituicao['nome'];
$sigla = $instituicao['sigla'];
$data = date("Y-m-d", strtotime("-3 hours")); // usado para realizar a conversão para que possa pegar o dia da semana
$diaSemana = diasemana($data);
$hoje = date("d/m/Y", strtotime("-3 hours"));
$valor = $pedidos['valor_total'];
$pagamento = $pedidos['forma_pagamento'];
$valor_extenso = valorPorExtenso($valor);
$periodo = retornaPeriodoNovo($idEvento, 'ocorrencias');


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
if ($pedidos['pessoa_tipo_id'] == 1) {
    $pessoa = recuperaDados("pessoa_fisicas", "id", $pedidos ['pessoa_fisica_id']);
    $y = $pessoa['nome'];
    if($pessoa['passaporte'] != NULL){
        $x = $pessoa['passaporte'];
    }else{
        $x = $pessoa['cpf'];
    }
    
} else if ($pedidos['pessoa_tipo_id'] == 2) {
    $pessoa = recuperaDados('pessoa_juridicas', "id", $pedidos['pessoa_juridica_id']);
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
    "<p>&nbsp;</p>" .
    "<p><strong>Locais e Horários:</strong> " . "</p>" .
    "<p>"."$nome_instituicao"."&nbsp;"."($sigla)"."<br>$periodo"."&nbsp;"."($diaSemana)"."&nbsp;às&nbsp;$hora_inicio</p>".
    "<p>&nbsp;</p>" .
    "<p><strong> Valor:</strong> " . "R$ " .$valor. "  " . "($valor_extenso)" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "$pagamento" . "</p>" .
    "<p><strong>Dotação Orçamentária:</strong>"."$dotacao"."&nbsp;"."</p>" .
    "<p>$finalizacao;</p>" .
    "<p align='justify'>" . "" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, ".$hoje. "</p>" .
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
