<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();

$idPedido = $_POST['idPedido'];
$tipo = $_POST['tipo'];

$pedido = $con->query("SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 2 AND publicado = 1")->fetch_array();
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$linguagem = recuperaDados('linguagens', 'id', $contratacao['linguagem_id'])['linguagem'];

$programa = recuperaDados('programas', 'id', $contratacao['programa_id'])['programa'];

switch ($tipo) {
    case "pia":
        $texto = "<p>Assim, solicito a reserva de recursos que deverá onerar a ação 6374 – Dotação 25.10.13.392.3001.6374</p>";
        break;
    case "sme":
        $texto = "<p>Assim, solicito a reserva de recursos, que deverá onerar os recursos da Nota de Reserva com Transferência da SME nº 22.671/2019 e para o INSS Patronal a Nota de Reserva com Transferência nº 22.711/2019 SEI (link do SEI)</p>";
        break;
    case "vocacional":
        $texto = "<p>Assim, solicito a reserva de recursos que deverá onerar a ação 6375 – Dotação 25.10.13.392.3001.6375</p>";
        break;
    default:
        $texto = "";
        break;
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
        "<p><strong>Do processo nº:</strong> " . $contratacao['protocolo'] . "</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>INTERESSADO:</strong> " . $pessoa['nome'] . "  </span></p>" .
        "<p><strong>Objeto:</strong> " . retornaObjetoFormacao_Emia($idFC, "formacao") . "</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>CONTABILIDADE</strong></p>" .
        "<p><strong>Sr(a). Responsável</strong></p>" .
        "<p>&nbsp;</p>" .
        "<p>O presente processo trata de " . $pessoa['nome'] . ", " . $programa . ", " . $linguagem . " NOS TERMOS DO EDITAL - " . $programa . ", no valor de " . "R$ " . "  " . dinheiroParaBr($pedido['valor_total']) . " ( ". valorPorExtenso($pedido['valor_total']) . " )" .  ", conforme solicitação (link da solicitação), foram anexados os documentos necessários exigidos no edital, no período de " . retornaPeriodoFormacao_Emia($contratacao['form_vigencia_id'], "formacao") . " </p>" .
        "<p>&nbsp;</p>" .
        $texto .
    "<p>&nbsp;</p>" .
    "<p>Após, enviar para SMC/AJ, para prosseguimento.</p>" .
    "<p>&nbsp;</p>"
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
