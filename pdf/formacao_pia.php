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
$modelo = recuperaDados('juridicos', 'pedido_id', $idFormacao);
$pessoa = recuperaDados('pessoa_fisicas','id',$idFormacao);
$linguagem = recuperaDados('linguagens', 'id', $fc['linguagem_id'])['linguagem'];
$programa = recuperaDados('programas', 'id', $fc['programa_id'])['programa'];
$edital = recuperaDados('programas', 'id', $fc['programa_id'])['edital'];
$pedido = recuperaDados('pedidos','id',$idFormacao);
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
$dataAtual = date("Y/m/d");
$diaSemana = diasemana($dataAtual);

//periodo
$data = retornaPeriodoFormacao($vigencia);
// local
$sqlLocal = "SELECT l.local FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' - ';
}

$local = substr($local, 0, -3);

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
    "<p><strong>Contratado:</strong> " . "$nome" . ", CPF (" . "$cpf" . ")</p>" .
    "<p><strong>Objeto:</strong> " . "$programa" . " " . "$linguagem" . " " . "$edital" . "</p>" .
    "<p><strong>Data / Período:</strong>" . "$data" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Locais e Horários</strong></p>" .
    "<p>"."$local"."</p>" .
    "<p>"."$data"."&nbsp;"."($diaSemana)"."</p>".
    "<p>&nbsp;</p>" .
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

</body>
</html>
