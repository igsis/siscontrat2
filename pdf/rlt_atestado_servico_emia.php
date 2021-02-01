<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();

$dia = date('d');
$mes = retornaMes(date('m'));
$ano = date('Y');
$semana = date('w');

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
        .infos {
            width: 100%;
            border: solid;
            padding: 50px;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: justify;
        }
    </style>
    <link rel="stylesheet" href="../visual/css/bootstrap.min.css">
    <link rel="stylesheet" href="../visual/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Atestado de Serviço</title>
</head>

<body>
<br>
<div align="center">
    <?php
    $conteudo =
        "<p><center><strong>DEC/Escola Municipal de Iniciação Artística</strong></center></p>" .
        "<p><center>Viaduto do Chá, 15, - Bairro Centro - São Paulo/SP - CEP 01020-900</center></p>" .
        "<p><center>Telefone:</center></p>" .
        "<p><center>Ateste de recebimento da documentação</center></p>" .
        "<p>&nbsp;</p>" .
        "<p><center>Anexo I da Portaria SF nº 170, de 31 agosto de 2020</center></p>" .
        "<p><center>Modelo de recebimento da documentação e ateste total/parcial de nota fiscal dentro/fora do prazo</center></p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Recebimento da Documentação</strong>" .
        "<p>Atesto:</p>" .
        "<p>( X ) o recebimento em ___/___/_____  de <strong>toda a documentação</strong> [INSERIR NÚMERO SEI DA NOTA FISCAL E ARQUIVOS CONSOLIDADOS] prevista na Portaria SF nº 170/2020.</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) o recebimento em ___/___/_____ da <b>documentação</b> [INSERIR NÚMERO SEI DA NOTA FISCAL E ARQUIVOS CONSOLIDADOS] prevista na Portaria SF nº 170/2020, <b>ressalvado</b> (s) [RELACIONAR OS DOCUMENTOS IRREGULARES].</p>" .
        "<p>&nbsp;</p>" .
        "<p><strong>Recebimento de material e/ou serviços</strong>" .
        "<p>Atesto:</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL] foram entregues e/ou executados a <b>contento</b> nos termos previstos no instrumento contratual (ou documento equivalente) no dia ___/___/_____, dentro do prazo previsto.</p>" .
        "<p>O prazo contratual é do dia ___/___/_____ até o dia ___/___/_____.</p>" .
        "<p>&nbsp;</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL] foram entregues e/ou executados <b>parcialmente</b>, nos termos previstos no instrumento contratual (ou documentos equivalente), do dia ___/___/_____, dentro do prazo previsto.</p>" .
        "<p>O prazo contratual é do dia ___/___/_____ até o dia ___/___/_____.</p>" .
        "<p>&nbsp;</p>" .
        "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) que os materiais/serviços prestados discriminados no documento fiscal [INSERIR NÚMERO SEI DA NOTA FISCAL] foram entregues e/ou executados a contendo nos termos previstos no instrumento contratual( ou documento equivalente) no dia ___/___/_____, <strong>com atraso</strong> de ___ dias.</p>" .
        "<p>O prazo contratual é do dia ___/___/_____ até o dia ___/___/_____.</p>" .
        "<p>&nbsp;</p>" .
        "<p style='background: lightgrey;'><strong>INFORMAÇÕES COMPLEMENTARES</strong></p>" .
        "<div class='infos'></div>" .
        "<p>&nbsp;</p>" .
        "<p>À área gestora / de liquidação e pagamento.</p>" .
        "<p>Em virtude do detalhamento da Ação em 2019, informamos que o pagamento no valor de R$ 4.194,72 (quatro mil, cento e noventa e quatro reais e setenta e dois centavos) foi gasto na zona sul de São Paulo, rua Volkswagen, s/nº, Jabaquara, SP.</p>" .
        "<p>Encaminho para prosseguimento.</p>" .
        "<p>&nbsp;</p>" .
        "<p><center>São Paulo,  $dia  de  $mes  de  $ano.</center></p>"
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