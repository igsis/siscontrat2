<?php
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();

$idFormacao = recuperaUltimo('formacao_contratacoes',);
$modelo = recuperaDados('juridicos', 'pedido_id', $idFormacao);
$amparo = $modelo['amparo_legal'];
$dotacao = $modelo['dotacao'];
$finalizacao = $modelo['finalizacao'];
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
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$amparo" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . "" . ", CPF (" . "" . ")</p>" .
    "<p><strong>Objeto:</strong> " . "" . "</p>" .
    "<p><strong>Data / Período:</strong>" . "" . "</p>" .
    "<p><strong>Locais:</strong> " . "  " . "" . "()" . " </p>" .
    "<p><strong>Carga Horária:</strong><p>" . "" . "</p>" .
    "<p><strong> Valor:</strong> " . "R$ " . "  " . "()" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "" . "</p>" .
    "<p><strong>Dotação Orçamentária: </strong> " . " $dotacao " . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$finalizacao" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, " . "" . "</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="dados" class="texto"><?php echo $dados; ?></div>
</div>

</body>
</html>
