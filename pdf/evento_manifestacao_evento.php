<?php

require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
isset($_POST['idEvento']);
$idEvento = $_POST['idEvento'];
isset($_POST['idPedido']);
$idPedido = $_POST['idPedido'];


$evento = recuperaDados('eventos','id',$idEvento);
$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$instituicoes = recuperaDados('instituicoes','id',$ocorrencia['instituicao_id']);
$pedidos = recuperaDados('pedidos','id',$idPedido);
$num_pro = $pedidos['numero_processo'];


$nome_eve = $evento['nome_evento'];
$nome_inst = $instituicoes['nome'];
$data = date("Y/m/d");
$hoje = date('d/m/Y');

if($num_pro == 'null'){
    $num_pro = "Número não gerado";
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
<?php

if ($pedidos['pessoa_tipo_id'] == 1) {
    $pessoa = recuperaDados("pessoa_fisicas", "id", $pedidos ['pessoa_fisica_id']);
    $y = $pessoa['nome'];
    $x = $pessoa['cpf'];
} else if ($pedidos['pessoa_tipo_id'] == 2) {
    $pessoa = recuperaDados('pessoa_juridicas', "id", $pedidos['pessoa_juridica_id']);
    $y = $pessoa['razao_social'];
    $x = $pessoa['cnpj'];
}
$dados =
    "<p align='justify'>" . "" . "</p>" .
    "<p><strong>Do processo Nº :</strong> " . "$num_pro" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>INTERESSADO:</strong> " . "$y" . " - (" . "$x" . ")</p>".
    "<p><strong>ASSUNTO:</strong> " . "$nome_eve" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>$nome_inst</strong></p>" .
    "<p>Sr(a) Diretor(a)</p>" .
    "<p>&nbsp;</p>" .
    "<p>Trata-se de proposta de contratação de serviços de natureza artística para <strong>" .$nome_inst. "</strong> conforme parecer da Comissão de Atividades Artísticas e Culturais, que ratifica o pedido, sustentando que “o espetáculo é composto por profissionais consagrados pelo público e pela crítica especializada, estando o cachê proposto de acordo com os praticados no mercado e pagos por esta Secretaria para artistas do mesmo renome”, tendo em vista, vale dizer, os <strong>artigos 25, III e 26, único, II e III da Lei Federal n° 8.666/93, c/c artigos 16 e 17 do Decreto Municipal n° 44.279/03.</strong></p>" .
    "<p align='justify'>Assim sendo, pela competência, encaminhamos-lhe o presente para deliberação final do(a) senhor(a), ressaltando que as certidões referentes à regularidade fiscal da contratada encontram-se em ordem.</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, " . $hoje . "</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="dados" class="texto"><?php echo $dados; ?></div>
</div>
<br>
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
</body>
</html>
