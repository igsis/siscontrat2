<?php
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

// conexão com banco //
$con = bancoMysqli();
session_start();


$idEvento = $_SESSION['eventoId'];

$modelo = recuperaDados('juridicos','pedido_id',$idEvento);
$amparo = $modelo['amparo_legal'];
$dotacao = $modelo['dotacao'];
$finalizacao = $modelo['finalizacao'];
$data = date("Y/m/d");

$sqlEvento = "SELECT 
        e.nome_evento,
        pf.nome,
        pf.cpf,
        
        FROM evento as e 
        INNER JOIN pedidos as p on e.id = p.id;
        INNER JOIN pessoa_fisicas as pf on p.pessoa_fisica_id = pf.id;
        WHERE id = $idEvento AND publicado = 1 ";

$evento = mysqli_query($con,$sqlEvento);
?>


<html>
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html. charset=Windows-1252\">

    <style>

        .texto{
            width: 900px;
            border: solid;
            padding: 20px;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            text-align:justify;
        }
    </style>
</head>




<body>
<?php
$dados =
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "$amparo" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Contratado:</strong> " . "" . ", CPF (" . "" . ")</p>" .
    "<p><strong>Objeto:</strong> " . "" . "</p>" .
    "<p><strong>Data / Período:</strong>".""."</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Locais e Horários:</strong> " . "</p>" .
    "<p>".""."&nbsp;"."()"."</p>".
    "<p>"."&nbsp;"."()"."&nbsp;ás&nbsp;</p>".
    "<p>&nbsp;</p>" .
    "<p><strong> Valor:</strong> " . "R$ " . "  " . "()" . "</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . "" . "</p>" .
    "<p><strong>Dotação Orçamentária: $dotacao </strong> " . "$finalizacao" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='justify'>" . "" . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'>São Paulo, "."$data"."</p>" .
    "<p>&nbsp;</p>"


?>
<div align="center">
    <div id="dados" class="texto"><?php echo $dados; ?></div>
</div>

</body>
</html>

