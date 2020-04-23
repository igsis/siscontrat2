<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$ocorrencias = $con->query("SELECT i.nome, o.atracao_id, o.tipo_ocorrencia_id FROM ocorrencias AS o INNER JOIN instituicoes AS i ON o.instituicao_id = i.id WHERE o.tipo_ocorrencia_id != 3 AND o.publicado = 1 AND o.origem_ocorrencia_id =  " . $evento['id']);

$setores = "";
$qtsApresentacao = 0;
$cargaHoraria = 0;

while ($linhaOco = mysqli_fetch_array($ocorrencias)) {
    $setores = $setores . $linhaOco['nome'] . '; ';

    if($linhaOco['tipo_ocorrencia_id'] == 1){
        $atracoes = $con->query("SELECT quantidade_apresentacao FROM atracoes WHERE publicado = 1 AND evento_id = " . $evento['id'] . " AND id = " . $linhaOco['atracao_id']);

        while($atracao = mysqli_fetch_array($atracoes)){
            $qtsApresentacao = $qtsApresentacao + (int)$atracao['quantidade_apresentacao'];
        }

        $trechoApresentacoes = ", totalizando $qtsApresentacao apresentações conforme proposta/cronograma";

        $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = " . $linhaOco['atracao_id'];
        $carga = $con->query($sqlCarga);

        if($carga->num_rows > 0){
            while($carga = mysqli_fetch_array($carga)){
                $cargaHoraria =  $cargaHoraria + (int)$carga['carga_horaria'];
            }
        }else{
            $cargaHoraria = "Não possuí.";
        }
    }
}

$setores = substr($setores, 0);

$idEvento = $evento['id'];
$idPj = $pedido['pessoa_juridica_id'];
$evento = recuperaDados('eventos', 'id', $idEvento);
$pessoa = recuperaDados('pessoa_juridicas', 'id', $idPj);

$sqlTelefone = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPj'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$sqlLocal = "SELECT l.local FROM locais AS l INNER JOIN ocorrencias AS o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = '$idEvento' AND o.publicado = 1";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' | ';
}

$local = substr($local, 0, -3);

$duracao = "Não se aplica";
if($evento['tipo_evento_id'] == 2){
    $duracao = 0;
    $sqlTestaFilme = "SELECT filme_id FROM filme_eventos WHERE evento_id = $idEvento";
    $queryFilme = mysqli_query($con, $sqlTestaFilme);

    while($idFilmes = mysqli_fetch_array($queryFilme)){
        $filme = $con->query("SELECT duracao FROM filmes WHERE id = " . $idFilmes['filme_id']);
        $duracao = $duracao + (int)$filme['duracao'];
    }
    $duracao = $duracao . "Minuto(s)";
}

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];

$fiscal = recuperaDados('usuarios', 'id', $evento['fiscal_id']);
$suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);

if ($pedido['numero_processo_mae'] != NULL) {
    $processoMae = "<p><strong>Processo SEI de reserva global:</strong> " . $pedido['numero_processo_mae'] . "</p>";
} else {
    $processoMae = NULL;
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
    <script src="include/dist/ZeroClipboard.min.js"></script>
</head>

<body>
<br>
<div align="center">
    <?php
    $conteudo =
        "<p align='center'><strong>PEDIDO DE  CONTRATAÇÃO DE PESSOA JURÍDICA</strong></p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Sr(a) </strong></p>" .
        "<p>Solicitamos a contratação a seguir:</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Pedido de Contratação nº:</strong> " . $evento['protocolo'] . "</p>" .
        "<p><strong>Processo SEI nº:</strong> " . $pedido['numero_processo'] . "</p>" .
        $processoMae .
        "<p><strong>Setor  solicitante:</strong> $setores </p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Razão Social:</strong> " . $pessoa['razao_social'] . " <br />" .
        "<strong>CNPJ:</strong> " . $pessoa['cnpj'] . "<br />" .
        "<strong>Telefone:</strong> " . $tel . "<br />" .
        "<strong>E-mail:</strong> " . $pessoa['email'] . "</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Objeto:</strong> " . $objeto . "</p>" .
        "<p><strong>Data / Período:</strong> " . retornaPeriodoNovo($idEvento,'ocorrencias') . $trechoApresentacoes  . "</p>" .
        "<p><strong>Carga Horária:</strong> " . $cargaHoraria . "</p>" .
        "<p><strong>Duração: </strong>" . $duracao  ."</p>".
        "<p align='justify'><strong>Local:</strong> " . $local . "</p>" .
        "<p><strong>Valor: </strong> R$ " . dinheiroParaBr($pedido['valor_total']) . " ( ". valorPorExtenso($pedido['valor_total']) . " )"  . "</p>" .
        "<p align='justify'><strong>Forma de Pagamento:</strong> " . $pedido['forma_pagamento'] . "</p>" .
        "<p align='justify'><strong>Justificativa: </strong> " . $pedido['justificativa'] . "</p>" .
        "<p align='justify'>Nos termos do art. 6º do decreto 54.873/2014, fica designado como fiscal desta contratação artística o(a) servidor(a) " . $fiscal['nome_completo'] . ", RF " . $fiscal['rf_rg'] . " e, como substituto, " . $suplente['nome_completo'] . ", RF " . $suplente['rf_rg'] . ". Diante do exposto, solicitamos autorização para prosseguimento do presente." . "</p>";
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

