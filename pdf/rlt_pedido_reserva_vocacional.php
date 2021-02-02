<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS 
$con = bancoMysqli();

//CONSULTA 
$id_ped = $_GET['idPedido'];

$ano = date('Y');

$sql = "SELECT p.id, fc.protocolo, fc.ano, p.numero_processo,
               fc.num_processo_pagto, pf.nome, v.verba, fs.status,
               p.valor_total, p.justificativa
            FROM pedidos p 
            INNER JOIN formacao_contratacoes fc ON fc.id = p.origem_id 
            INNER JOIN pessoa_fisicas pf ON fc.pessoa_fisica_id = pf.id
            INNER JOIN verbas v on p.verba_id = v.id 
            INNER JOIN formacao_status fs on fc.form_status_id = fs.id
            WHERE  p.id = {$id_ped}";

$query = mysqli_query($con,$sql);
$pedido = mysqli_fetch_assoc($query);

$valorPorExtenso = valorPorExtenso($pedido['valor_total'])

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
echo var_dump($pedido);
$sei =
    "<p><strong>Do processo nº:</strong> {$pedido['numero_processo']} </p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>INTERESSADO:</strong> {$pedido['nome']} </span></p>" .
    "<p><strong>ASSUNTO:</strong> {$pedido['justificativa']}  </p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>CONTABILIDADE</strong></p>" .
    "<p><strong>Sr(a). Responsável</strong></p>" .
    "<p>&nbsp;</p>" .
    "<p>O presente processo trata de {$pedido['nome']}, no valor de R$ {$pedido['valor_total']} ({$valorPorExtenso})  conforme solicitação (link da solicitação), foram anexados os documentos necessários exigidos no edital, no período de </p>" .
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