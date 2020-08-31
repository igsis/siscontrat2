<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


//CONSULTA
$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);

$periodo = retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias');

$ano = date('Y');

$instituicoes = "";
$consultaInst = $con->query("SELECT i.nome FROM instituicoes AS i INNER JOIN ocorrencias AS o ON i.id = o.instituicao_id WHERE o.origem_ocorrencia_id = " . $evento['id'] . " AND o.publicado = 1");
if ($consultaInst->num_rows > 0) {
    while ($instituicaoArray = mysqli_fetch_array($consultaInst)) {
        $instituicoes = $instituicoes . $instituicaoArray['nome'] . "/";
    }
    $instituicoes = substr($instituicoes, 0, -1);
}

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=rlt_direitos_conexos.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<body>";

echo
    "<p align='center'><strong>AUTORIZAÇÃO DE USO DE DIREITOS CONEXOS</strong></p>" .
    "<p>&nbsp;</p>" .
    "<p>Nós, abaixo assinadas, AUTORIZAMOS, gratuitamente, a PREFEITURA DO MUNICÍPIO DE SÃO PAULO, por meio da Secretaria Municipal de Cultura/" . $instituicoes .
    ", a utilizar os direitos conexos relativos às gravações de áudio e vídeo da nossa participação, captado no concerto a ser realizado em " . $periodo .
    " no(s) local(is) " . listaLocais($evento['id'], '1') . ", para fins de inserção no site, exclusivamente para fins não comerciais, pelo prazo de proteção do artigo 96 da Lei 9.610/98." . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>Data: ____ / ____ / " . $ano . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>Integrantes:</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>Assinaturas:</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>";

echo "</body>";
echo "</html>";
?>

