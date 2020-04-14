<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$pessoa = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
$enderecos = recuperaDados('pj_enderecos', 'pessoa_juridica_id', $pedido['pessoa_juridica_id']);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$periodo = retornaPeriodoNovo($pedido['origem_id'],'ocorrencias');

$idRepresentante = $pessoa['representante_legal1_id'];
$representante = recuperaDados('representante_legais', 'id', $idRepresentante);

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
<p align='justify'>DECLARO para os devidos fins, que a empresa <?=$pessoa['razao_social']?>, CNPJ: <?=$pessoa['cnpj']?>, sediada na <?=$enderecos['logradouro']?>, <?=$enderecos['numero']?>/ <?=$enderecos['complemento']?> - <?=$enderecos['bairro']?> - <?=$enderecos['cidade']?> / <?=$enderecos['uf']?>,
    está ciente e de acordo que o pagamento dos serviços a serem prestados, referente a <?=$objeto?>, <?=$periodo?>, no local(ais) <?=$local?>,
    ficará condicionado à apresentação do documento, abaixo listado, regularizado: </p>
<br>
<p align='justify'><?=$dataAtual?></p>
<br>
<strong align='justify'>__________________________________________________________________ </strong>
<p align='justify'><?=$pessoa['razao_social']?></p>
<p>&nbsp;</p>
<p align='justify'><?=$representante['nome']?></p>
<p align='justify'>CPF: <?=$representante['cpf']?></p>
</body>
</html>
