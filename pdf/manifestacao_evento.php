<?php

require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
session_start();

$idEvento = $_SESSION['eventoId'];
$instituicoes = recuperaDados('instituicoes','id',$idEvento);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idEvento);
$evento = recuperaDados('eventos','id',$idEvento);
$pedidos = recuperaDados('pedidos','id',$idEvento);
$num_pro = $pedidos['numero_processo'];
$nome_eve = $evento['nome_evento'];
$nome = $pessoa['nome'];
$nome_inst = $instituicoes['nome'];
$data = date("Y/m/d");

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
</head>

<body>
<?php
$dados =
    "<p align='justify'>" . "" . "</p>" .
    "<p><strong>Do processo Nº :</strong> " . "$num_pro" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>INTERESSADO:</strong> " . "$nome" . "</p>" .
    "<p><strong>ASSUNTO:</strong> " . "$nome_eve" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>$nome_inst</strong></p>" .
    "<p>Sr(a) Diretor(a)</p>" .
    "<p>&nbsp;</p>" .
    "<p>Trata-se de proposta de contratação de serviços de natureza artística para <strong>" .$nome_inst. "</strong> conforme parecer da Comissão de Atividades Artísticas e Culturais, que ratifica o pedido, sustentando que “o espetáculo é composto por profissionais consagrados pelo público e pela crítica especializada, estando o cachê proposto de acordo com os praticados no mercado e pagos por esta Secretaria para artistas do mesmo renome”, tendo em vista, vale dizer, os <strong>artigos 25, III e 26, §único, II e III da Lei Federal n° 8.666/93, c/c artigos 16 e 17 do Decreto Municipal n° 44.279/03.</strong></p>" .
    "<p align='justify'>Assim sendo, pela competência, encaminhamos-lhe o presente para deliberação final do(a) senhor(a), ressaltando que as certidões referentes à regularidade fiscal da contratada encontram-se em ordem.</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, " . $data . "</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="dados" class="texto"><?php echo $dados; ?></div>
</div>

</body>
</html>
