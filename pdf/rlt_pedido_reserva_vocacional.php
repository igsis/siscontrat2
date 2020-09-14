<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS 
$conexao = bancoMysqli();

//CONSULTA 
$id_ped = $_GET['idPedido'];

$ano = date('Y');

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
    <script src="include/dist/ZeroClipboard.min.js"></script>
</head>

<body>


<?php

$sei =
    "<p><strong>Do processo nº:</strong> </p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>INTERESSADO:</strong>  </span></p>" .
    "<p><strong>ASSUNTO:</strong>   </p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>CONTABILIDADE</strong></p>" .
    "<p><strong>Sr(a). Responsável</strong></p>" .
    "<p>&nbsp;</p>" .
    "<p>O presente processo trata de , no valor de  conforme solicitação (link da solicitação), foram anexados os documentos necessários exigidos no edital, no período de </p>" .
    "<p>&nbsp;</p>" .
    "<p>Assim, solicito a reserva de recursos que deverá onerar a ação 6375  – Dotação 25.10.13.392.3001.6375</p>" .
    "<p>&nbsp;</p>" .
    "<p>Após, enviar para SMC/AJ,  para prosseguimento.</p>" .
    "<p>&nbsp;</p>"

?>

<div align="center">
    <div id="texto" class="texto"><?php echo $sei; ?></div>
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