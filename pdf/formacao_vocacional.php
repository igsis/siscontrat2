<?php
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();

isset($_POST['idFormacao']);
$idFormacao = $_POST['idFormacao'];


$fc = recuperaDados('formacao_contratacoes','id',$idFormacao);
$vigencia = $fc['form_vigencia_id'];
$pessoa = recuperaDados('pessoa_fisicas','id',$fc['pessoa_fisica_id']);
$linguagem = recuperaDados('linguagens', 'id', $fc['linguagem_id'])['linguagem'];
$programa = recuperaDados('programas', 'id', $fc['programa_id'])['programa'];
$edital = recuperaDados('programas', 'id', $fc['programa_id'])['edital'];
$pedido = recuperaDados('pedidos','id',$fc['pedido_id']);
$modelo = recuperaDados('juridicos', 'pedido_id', $idFormacao);
$pagamento = $pedido['forma_pagamento'];
$valorT = $pedido['valor_total'];
$valor_extenso = valorPorExtenso($valorT);
$nome = $pessoa['nome'];
$cpf = $pessoa['cpf'];
$amparo = $modelo['amparo_legal'];
$dotacao = $modelo['dotacao'];
$finalizacao = $modelo['finalizacao'];
$fp = recuperaDados('formacao_parcelas','id',$idFormacao);
$carga = $fp['carga_horaria'];
$dataAtual = date("d/m/Y", strtotime("-3 hours"));

//periodo
$data = retornaPeriodoFormacao($vigencia);
// local
$sqlLocal = "SELECT l.local 
FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' - ';
}

$local = substr($local, 0, -3);

if($pessoa['passaporte' != NULL]){
    $cpf_passaporte = "Passaporte (" . $pessoa['passaporte'] . ")</p>";
}else{
    $cpf_passaporte = "CPF (" . $cpf . ")</p>";
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
</head>


<body>
<?php
$dados =
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$amparo" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . "$nome" . ", $cpf_passaporte" .
    "<p><strong>Objeto:</strong> " . "$programa" . " " . "$linguagem" . " " . "$edital" . "</p>" .
    "<p><strong>Data / Período:</strong>" . "$data" . "</p>" .
    "<p><strong>Locais:</strong> " . "  " . "$local" . "</p>" .
    "<p><strong>Carga Horária:</strong>"."" . "$carga" . "" .
    "<p><strong> Valor:</strong> " . "R$ $valorT " . "  " . "($valor_extenso)" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "$pagamento" . "</p>" .
    "<p><strong>Dotação Orçamentária: </strong> " . " $dotacao " . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$finalizacao" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, " . "$dataAtual" . "</p>" .
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
