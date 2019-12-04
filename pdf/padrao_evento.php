<?php

require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
session_start();


$idEvento = $_SESSION['eventoId'];

if (isset($_POST['geraPadrao'])) {
    $modeloPadrao = $_POST['geraPadrao'];
}
if(isset($_POST['idModelo'])){
    $idModelo = $_POST['idModelo'];
}


$evento = recuperaDados('eventos','id',$idEvento);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idEvento);
$pedidos = recuperaDados('pedidos','id',$idEvento);
$ocorrencias = recuperaDados('ocorrencias','id',$idEvento);
$instituicao = recuperaDados('instituicoes', 'id', $ocorrencias['instituicao_id']);
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
    "<p align='justify'>" . "I – À vista dos elementos constantes do presente, em especial o Parecer da Comissão de Atividades Artísticas e Culturais n° , diante da competência a mim delegada pela Portaria nº 17/2018-SMC/G, AUTORIZO, com fundamento no artigo 25, inciso III, da Lei Federal nº 8.666/93, a contratação nas condições abaixo estipuladas, observada a legislação vigente e demais cautelas legais:" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . "$nome" . ", CPF (" . "$cpf" . ")</p>" .
    "<p><strong>Objeto:</strong> " . "$nome_evento" . "</p>" .
    "<p><strong>Data / Período:</strong>"."$periodo"."</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Locais e Horários:</strong> " . "</p>" .
    "<p>"."$nome_instituicao"."&nbsp;"."($sigla)"."</p>".
    "<p>$periodo"."&nbsp;"."($diaSemana)"."&nbsp;ás&nbsp;$hora_inicio</p>".
    "<p>&nbsp;</p>" .
    "<p><strong> Valor:</strong> " . "R$ " .$valor. "  " . "($valor_extenso)" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "$pagamento" . "</p>" .
    "<p><strong>Dotação Orçamentária:</strong> " . "" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "II - Nos termos do art. 6º do Decreto nº 54.873/2014, designo o(a) servidor(a) Stefani de Oliveira Trindade, RF 8390754, como fiscal do contrato e o(a) servidor(a) Lucia Ágata, RF d604737, como seu substituto. III - Autorizo a emissão da competente nota de empenho de acordo com o Decreto Municipal nº 58.070/2018 e demais normas de execução orçamentárias vigentes. IV - Publique-se e encaminhe-se à CAF/Contabilidade para as providências cabíveis." . "</p>" .
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
