<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);

$data = date("Y-m-d", strtotime("-3 hours"));

$dia = date("d");

$mes = date("m");

$ano = date("Y");

switch ($mes) {

    case 1:
        $mes = "Janeiro";
        break;
    case 2:
        $mes = "Fevereiro";
        break;
    case 3:
        $mes = "Março";
        break;
    case 4:
        $mes = "Abril";
        break;
    case 5:
        $mes = "Maio";
        break;
    case 6:
        $mes = "Junho";
        break;
    case 7:
        $mes = "Julho";
        break;
    case 8:
        $mes = "Agosto";
        break;
    case 9:
        $mes = "Setembro";
        break;
    case 10:
        $mes = "Outubro";
        break;
    case 11:
        $mes = "Novembro";
        break;
    case 12:
        $mes = "Dezembro";
        break;
}

$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);

$idCoord = $contratacao['coordenadoria_id'];
$coordenadoria = recuperaDados('coordenadorias','id', $idCoord);



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
        "<p>&nbsp;</p>".
        "<p><strong>Interessado:</strong> ".$pessoa['nome']."</p>".
        "<p><strong>Programa:</strong> " . $programa['programa'] . " <strong>Linguagem:</strong> " . $linguagem['linguagem'] . " <strong>Edital:</strong> " . $programa['edital'] . "</p>" .
        "<p>&nbsp;</p>".
        "<p>Atesto o recebimento em ".exibirDataBr($data).", de toda a documentação: recibo link SEI e arquivos consolidados, previstos na Portaria SF 08/16.</p>".

        "<p>&nbsp;</p>".
        "<p><strong>SMC - CONTABILIDADE</strong></p>".
        "<p><strong>Sr.(a) Contador(a)</strong></p>".
        "<p align='justify'>Encaminho o presente para providências quanto ao pagamento, uma vez que os serviços foram realizados e confirmados a contento conforme documento link SEI.</p>".
        "<p align='justify'>Em virtude da Regionalização e Georreferenciamento das Despesas Municipais com a nova implantação do Detalhamento da Ação em 2019 no Sistema SOF,  informamos que os valores do presente pagamento foram gastos na região ".$coordenadoria['coordenadoria'].".</p>".
        "<p>&nbsp;</p>".

        "<p>INFORMAÇÕES COMPLEMENTARES</p>".
        "<hr />".
        "<p><strong>Nota de Empenho:</strong></p>".
        "<p><strong>Anexo Nota de Empenho:</strong></p>".
        "<p><strong>Recibo da Nota de Empenho:</strong></p>".
        "<p><strong>Pedido de Pagamento:</strong></p>".
        "<p><strong>Recibo de pagamento:</strong></p>".
        "<p><strong>Relatório de Horas Trabalhadas:</strong></p>".
        "<p><strong>NIT/PIS/PASEP:</strong></p>".
        "<p><strong>Certidões fiscais:</strong></p>".
        "<p><strong>CCM:</strong></p>".
        "<p><strong>FACC:</strong></p>".
        "<p>&nbsp;</p>".

        "<p>São Paulo, ".$dia." de ".$mes." de ".$ano.".</p>";
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