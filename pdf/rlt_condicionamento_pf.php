<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
$enderecos = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $pedido['pessoa_fisica_id']);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$periodo = retornaPeriodoNovo($pedido['origem_id'],'ocorrencias');

$ano = date('Y', strtotime('-3 Hours'));

$dataAtual = date('d/m/Y', strtotime('-3 Hours'));


if($pessoa['passaporte'] != NULL){
    $trecho_rg_cpf_passaporte = " Passaporte: " . $pessoa['passaporte'];
    $label = "<p align='justify'>Passaporte: " . $pessoa['passaporte'] . "</p>";
}else{
    $trecho_rg_cpf_passaporte = " CPF: " . $pessoa['cpf'];
    $label = "<p align='justify'>CPF: " . $pessoa['cpf'] . "</p>";
}

// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI " . $pedido['numero_processo'] . " - Condicionamento.doc");

?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>

<p align="center">DECLARAÇÃO</p>
<br>
<p align='justify'>DECLARO para os devidos fins, que eu <?=$pessoa['nome']?>, <?=$trecho_rg_cpf_passaporte?>, sediada na <?=$enderecos['logradouro']?>, <?=$enderecos['numero']?>/ <?=$enderecos['complemento']?> - <?=$enderecos['bairro']?> - <?=$enderecos['cidade']?> / <?=$enderecos['uf']?>,
    estou ciente e de acordo que o pagamento dos serviços a serem prestados, referente a <?=$objeto?>, <?=$periodo?>, no(s) local(ais) <?=listaLocais($evento['id'], '1')?>,
    ficará condicionado à apresentação do documento, abaixo listado, regularizado: </p>
<br>
<p align='justify'><?=$dataAtual?></p>
<br>
<strong align='justify'>__________________________________________________________________ </strong>
<p align='justify'><?=$pessoa['nome']?></p>
<?=$label?>
<p>&nbsp;</p>
</body>
</html>
