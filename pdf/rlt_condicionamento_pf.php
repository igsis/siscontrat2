<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();
session_start();

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
$enderecos = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $pedido['pessoa_fisica_id']);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$periodo = retornaPeriodoNovo($pedido['origem_id'],'ocorrencias');

$idEvento = $pedido['origem_id'];

$sqlLocal = "SELECT l.local FROM locais AS l INNER JOIN ocorrencias AS o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = '$idEvento' AND o.publicado = 1";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' | ';
}

$local = substr($local, 0, -3);

$ano = date('Y', strtotime('-3 Hours'));

$dataAtual = date('d/m/Y', strtotime('-3 Hours'));


// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI " . $pedido['numero_processo'] . " - Condicionamento.doc");

?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>

<p align="center">DECLARAÇÃO</p>
<br>
<p align='justify'>DECLARO para os devidos fins, que eu <?=$pessoa['nome']?>, CPF <?=$pessoa['cpf']?>, sediada na <?=$enderecos['logradouro']?>, <?=$enderecos['numero']?>/ <?=$enderecos['complemento']?> - <?=$enderecos['bairro']?> - <?=$enderecos['cidade']?> / <?=$enderecos['uf']?>,
    estou ciente e de acordo que o pagamento dos serviços a serem prestados, referente a <?=$objeto?>, <?=$periodo?>, no local(ais) <?=$local?>,
    ficará condicionado à apresentação do documento, abaixo listado, regularizado: </p>
<br>
<p align='justify'><?=$dataAtual?></p>
<br>
<strong align='justify'>__________________________________________________________________ </strong>
<p align='justify'><?=$pessoa['nome']?></p>
<p align='justify'>CPF: <?=$pessoa['cpf']?></p>
<p>&nbsp;</p>
</body>
</html>
