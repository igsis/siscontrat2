<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];


$pedido = recuperaDados('pedidos', 'id', $idPedido);
$fc = recuperaDados('formacao_contratacoes', 'id', $pedido['origem_id']);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);

$idLinguagem = $fc['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $fc['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);

$objeto = $programa['programa'] . " - " . $linguagem['linguagem'];

$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = " . $fc['id'];
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' | ';
}

$local = substr($local, 0, -3);

$carga = null;
$sqlCarga = "SELECT carga_horaria FROM formacao_parcelas WHERE formacao_vigencia_id = " .$fc['form_vigencia_id'];
$queryCarga = mysqli_query($con,$sqlCarga);

while ($countt = mysqli_fetch_array($queryCarga)){
    $carga += $countt['carga_horaria'];
}

$dotacao = $con->query("SELECT acao FROM verbas WHERE id = " . $programa['verba_id'])->fetch_array()['acao'];
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
        "<p>&nbsp;</p>" .
        "<p align='justify'>I - À vista dos elementos constantes do presente, em especial da seleção realizada conforme Edital de chamamento para credenciamento de  Artistas-Educadores e Coordenadores Artístico-Pedagógicos do " . $programa['programa'] . " - " . $programa['edital'] . ", para atuar nos equipamentos públicos da Secretaria Municipal de Cultura e nos CEUS (Centros Educacionais Unificados)  da Secretaria Municipal de Educação na edição de 2020, publicado no DOC de  24/10/2019  (link SEI), no uso da competência a mim delegada pela Portaria nº 17/2018 - SMC/G , AUTORIZO com fundamento no artigo 25 “caput”, da Lei Federal nº 8.666/93, a contratação nas condições abaixo estipuladas, observada a legislação vigente e demais cautelas legais:</p>" .
        "<p><strong>Contratado:</strong> " . $pessoa['nome'] . ", CPF (" . $pessoa['cpf'] . ")</p>" .
        "<p><strong>Objeto:</strong> " . $objeto . "</p>" .
        "<p><strong>Data / Período:</strong> " . retornaPeriodoFormacao($fc['form_vigencia_id']) . "</p>" .
        "<p><strong>Local(ais):</strong> " . $local . "</p>" .
        "<p><strong>Carga Horária:</strong> " . $carga . " Hora(s)" . "</p>" .
        "<p><strong>Valor:</strong> R$ " . dinheiroParaBr($pedido['valor_total']) . " (" . valorPorExtenso($pedido['valor_total']) . " )" . "</p>" .
        "<p><strong>Forma de Pagamento:</strong> Os valores devidos ao contratado serão apurados mensalmente de acordo com as horas efetivamente trabalhadas e pagos a partir do 1° dia útil do mês subseqüente ao trabalhado, desde que comprovada a execução dos serviços através da entrega à Supervisão de Formação Artística e Cultural dos documentos modelos preenchidos corretamente, sem rasuras, além da entrega do Relatório de Horas Trabalhadas atestadas pelo equipamento vinculado e, apenas para os artistas educadores/orientadores, as Listas de Presença de cada turma, nos termos do item 13.1 do Edital.</p>" .
        "<p><strong>Dotação Orçamentária:</strong>" . $dotacao . "</p>" .
        "<p align='justify'>II - Nos termos do art. 6º do Decreto nº 54.873/2014, designo a servidora Natalia Silva Cunha, RF 842.773.9,  como fiscal do contrato, e  Ilton T. Hanashiro Yogi, RF n.º 800.116.2, como suplente.</p>" .
        "<p align='justify'>III - Publique-se e encaminhe-se ao setor competente para providências cabíveis.</p>" .
        "<p>&nbsp;</p>" .
        "<p>&nbsp;</p>" .
        "<p>&nbsp;</p>" .
        "<p align='center'><b>Chefe de Gabinete<br/>S.M.C</b></p>" .
        "<p>&nbsp;</p>"
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


