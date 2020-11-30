<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();


$idParcela = $_POST['idParcela'];
$idPedido = $_POST['idPedido'];
$pedido = $con->query("SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 3 AND publicado = 1")->fetch_array();
$idEC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$contratacao = recuperaDados('emia_contratacao', 'id', $idEC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

$nomeFiscal = "";
$rfFiscal = "";
if ($contratacao['fiscal_id'] != 0) {
    $fiscal = recuperaDados('usuarios', 'id', $contratacao['fiscal_id']);
    if ($fiscal) {
        $nomeFiscal = $fiscal['nome_completo'];
        $rfFiscal = $fiscal['rf_rg'];
    }
}

$nomeSuplente = "";
$rfSuplente = "";
if ($contratacao['suplente_id'] != 0) {
    $suplente = recuperaDados('usuarios', 'id', $contratacao['suplente_id']);
    if ($suplente) {
        $nomeSuplente = $suplente['nome_completo'];
        $rfSuplente = $suplente['rf_rg'];
    }
}


$cargo = recuperaDados('emia_cargos', 'id', $contratacao['emia_cargo_id']);

$dia = date('d');
$mes = retornaMes(date('m'));
$ano = date('Y');
$semana = date('w');

$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND id = '$idParcela' AND publicado = 1";
$query = mysqli_query($con, $sqlParcelas);
while ($parcela = mysqli_fetch_array($query)) {
    $datapgt = exibirDataBr($parcela['data_pagamento']);
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
        "<p><strong><u><center>ATESTADO DE CONFIRMAÇÃO DE SERVIÇOS</strong></p></u></center>" .
        "<p>&nbsp;</p>" .
        "<p>Informamos que os serviços prestados pelo(a): " . $pessoa['nome'] . "</p>" .
        "<p><strong>Cargo: </strong>" . $cargo['cargo'] . "</p>" .
        "<p><strong>NA: </strong> EMIA </p>" .
        "<P><strong>DIA(S) / HORÁRIO(S): </strong>" . retornaPeriodoFormacao_Emia($contratacao['emia_vigencia_id'], "emia") . "</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS</p>" .
        "<p>( X ) FORAM REALIZADOS A CONTENTO</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS A CONTENTO, PELO SEGUINTE MOTIVO:</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>DADOS DO SERVIDOR (A) QUE ESTÁ CONFIRMANDO OU NÃO A REALIZAÇÃO DOS SERVIÇOS:</strong></p>" .
        "<p><strong>NOME LEGÍVEL:</strong> " . checaCampo($nomeFiscal) . "</p>" .
        "<p><strong>TELEFONE DE CONTATO:</strong> (11) 5017-2192</p>" .
        "<p><strong>LOTAÇÃO:</strong> EMIA-Escola Municipal de Iniciação Artística</p>" .
        "<p><strong>REGISTRO FUNCIONAL: </strong>" . checaCampo($rfFiscal) . "</p>" .
        "<p><strong>SUPLENTE:</strong> " . checaCampo($nomeSuplente) . "</p>" .
        "<p><strong>TELEFONE DE CONTATO:</strong> (11) 5017-2192</p>" .
        "<p><strong>LOTAÇÃO:</strong> EMIA-Escola Municipal de Iniciação Artística</p>" .
        "<p><strong>REGISTRO FUNCIONAL:</strong> " . checaCampo($rfSuplente) . "</p>" .
        "<p>&nbsp;</p>" .
        "<p>Com base na Folha de Frequência Individual: (Documento SEI link ) atesto que os materiais/serviços prestados discriminados no documento fiscal (Documento SEI link )  foram entregues e/ou executados a contento nos termos previstos no instrumento contratual (ou documento equivalente) no dia: " . $datapgt . ", dentro do prazo previsto. O prazo contratual é do dia " . retornaPeriodoFormacao_Emia($contratacao['emia_vigencia_id'], "emia") . ". </p>" .
        "<p>&nbsp;</p>" .
        "<p><strong><center>INFORMAÇÕES COMPLEMENTARES</strong></p></center>" .
        "<p>À área gestora/de liquidação e pagamento:</p>" .
        "<p>Encaminho para prosseguimento.</p>" .
        "<p>São Paulo,  $dia  de  $mes  de  $ano.  </p>"
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


