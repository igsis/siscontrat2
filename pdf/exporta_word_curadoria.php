<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


//CONSULTA
$idEvento = $_POST['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);
$pedido = recuperaDados('pedidos', 'origem_id', $idEvento);

$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);

$atracao = recuperaDados('atracoes', 'evento_id', $idEvento);

$classificacao = $con->query("SELECT classificacao_indicativa FROM classificacao_indicativas WHERE id = " . $atracao['classificacao_indicativa_id'])->fetch_array();

$ano = date('Y');
$dataAtual = date("d/m/Y");

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=". $pedido['numero_processo']. " em $dataAtual.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<body>";

echo "<p><b>CONTRATANTE:</b> "."Secretaria Municipal de Cultura"."</p>";
echo "<p align='justify'><b>Nome do Evento:</b>".$evento['nome_evento']."</p>";
echo "<p align='justify'><b>Sinopse:</b>" . $evento['sinopse'] . "</p>";
echo "<p align='justify'><b>Ficha Técnica: </b>". $atracao['ficha_tecnica'] .".</p>";
echo "<p align='justify'><b>Classificação Indicativa: </b>". $classificacao['classificacao_indicativa'] .".</p>";
echo "</body>";
echo "</html>";
?>

