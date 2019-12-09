<?php

require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
session_start();

$idEvento = $_SESSION['eventoId'];


$evento = recuperaDados('eventos','id',$idEvento);
$modelo_juridico = recuperaDados('juridicos','pedido_id',$idEvento);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idEvento);
$pedidos = recuperaDados('pedidos','id',$idEvento);
$ocorrencias = recuperaDados('ocorrencias','id',$idEvento);
$instituicao = recuperaDados('instituicoes', 'id', $ocorrencias['instituicao_id']);
$finalizacao = $modelo_juridico['finalizacao'];
$amparo = $modelo_juridico['amparo_legal'];
$dotacao = $modelo_juridico['dotacao'];
$hora_inicio = $ocorrencias['horario_inicio'];
$nome_evento = $evento['nome_evento'];
$nome_instituicao = $instituicao['nome'];
$sigla = $instituicao['sigla'];
$nome = $pessoa['nome'];
$cpf = $pessoa['cpf'];
$data = date("Y/m/d");
$diaSemana = diasemana($data);
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
$dados =
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$amparo" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . "$nome" . ", CPF (" . "$cpf" . ")</p>" .
    "<p><strong>Objeto:</strong> " . "$nome_evento" . "</p>" .
    "<p><strong>Data / Período:</strong>"."$periodo"."</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Locais e Horários:</strong> " . "</p>" .
    "<p>"."$nome_instituicao"."&nbsp;"."($sigla)"."<br>$periodo"."&nbsp;"."($diaSemana)"."&nbsp;ás&nbsp;$hora_inicio</p>".
    "<p>&nbsp;</p>" .
    "<p><strong> Valor:</strong> " . "R$ " .$valor. "  " . "($valor_extenso)" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "$pagamento" . ". Os valores devidos aos prestadores de serviços serão apurados mensalmente e pagos a partir do 1º dia útil do mês subsequente da comprovada execução dos serviços, mediante confirmação pela unidade responsável pela fiscalização.</p>" .
    "<p><strong>Dotação Orçamentária:</strong>"."$dotacao"."&nbsp;"."</p>" .
    "<p>$finalizacao</p>" .
    "<p align='justify'>" . "" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, ".$data. "</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="dados" class="texto"><?php echo $dados; ?></div>
</div>

</body>
</html>