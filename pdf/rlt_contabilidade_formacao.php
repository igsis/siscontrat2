<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();

$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);

$data = date("Y-m-d", strtotime("-3 hours"));

$dia = date("d");

$mes = retornaMes(date("m"));

$ano = date("Y");

$coordenadoria = recuperaDados('coordenadorias', 'id', $contratacao['coordenadoria_id'])['coordenadoria'];


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
        "<p><strong>Interessado:</strong> " . $pessoa['nome'] . "</p>" .
        "<p><strong>Objeto:</strong> " . retornaObjetoFormacao_Emia($idFC, "formacao") . "</p>" .
        "<p>&nbsp;</p>" .
        "<p>Atesto o recebimento em " . exibirDataBr($data) . ", de toda a documentação: recibo link SEI e arquivos consolidados, previstos na Portaria SF 08/16.</p>" .

        "<p>&nbsp;</p>" .
        "<p><strong>SMC - CONTABILIDADE</strong></p>" .
        "<p><strong>Sr.(a) Contador(a)</strong></p>" .
        "<p align='justify'>Encaminho o presente para providências quanto ao pagamento, uma vez que os serviços foram realizados e confirmados a contento conforme documento link SEI.</p>" .
        "<p align='justify'>Em virtude da Regionalização e Georreferenciamento das Despesas Municipais com a nova implantação do Detalhamento da Ação em 2019 no Sistema SOF,  informamos que os valores do presente pagamento foram gastos na região $coordenadoria." . "</p>" .
        "<p>&nbsp;</p>" .

        "<p>INFORMAÇÕES COMPLEMENTARES</p>" .
        "<hr />" .
        "<p><strong>Nota de Empenho:</strong></p>" .
        "<p><strong>Anexo Nota de Empenho:</strong></p>" .
        "<p><strong>Recibo da Nota de Empenho:</strong></p>" .
        "<p><strong>Pedido de Pagamento:</strong></p>" .
        "<p><strong>Recibo de pagamento:</strong></p>" .
        "<p><strong>Relatório de Horas Trabalhadas:</strong></p>" .
        "<p><strong>NIT/PIS/PASEP:</strong></p>" .
        "<p><strong>Certidões fiscais:</strong></p>" .
        "<p><strong>CCM:</strong></p>" .
        "<p><strong>FACC:</strong></p>" .
        "<p>&nbsp;</p>" .

        "<p>São Paulo, $dia de $mes de $ano</p>";
    ?>

    <div id="texto" class="texto"><?php echo $conteudo; ?></div>
</div>

<p>&nbsp;</p>

<div align="center">
    <button id="botao-copiar" class="btn btn-primary" onclick="copyText(getElementById('texto'))">
        COPIAR TODO O TEXTO
        <i class="fa fa-copy"></i>
    </button>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
        <button class="btn btn-primary">CLIQUE AQUI PARA ACESSAR O <img src="../visual/images/logo_sei.jpg"></button>
    </a>
</div>

<script>
    function copyText(element) {
        var range, selection, worked;

        if (document.body.createTextRange) {
            range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        } else if (window.getSelection) {
            selection = window.getSelection();
            range = document.createRange();
            range.selectNodeContents(element);
            selection.removeAllRanges();
            selection.addRange(range);
        }

        try {
            document.execCommand('copy');
            alert('Copiado com sucesso!');
            selection.removeAllRanges();
        } catch (err) {
            alert('Texto não copiado, tente novamente.');
            selection.removeAllRanges();
        }
    }
</script>

</body>
</html>