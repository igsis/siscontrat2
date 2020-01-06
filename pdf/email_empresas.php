<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT e.id AS idEvento, p.numero_processo, e.nome_evento,  rl.nome
    FROM pedidos p 
    INNER JOIN eventos AS e ON p.origem_id = e.id 
    INNER JOIN pessoa_juridicas pj on p.pessoa_juridica_id = pj.id
    INNER JOIN representante_legais rl on pj.representante_legal1_id = rl.id
    WHERE p.publicado = 1 AND p.id = '$idPedido'")->fetch_assoc();

$periodo = retornaPeriodo($pedido['idEvento']);

$modeloEmail = $_GET['modelo'];

switch ($modeloEmail) {
    case 'empresas':
        $item4 = "Declaração do Simples Nacional (para ser assinada pelo(a) representante legal, somente em caso de Empresa optante pelo Simples Nacional).";
        break;
    case 'cooperativas':
        $item4 = "Documento comprobatório quanto a isenção ou imunidade de impostos.";
        break;
    case 'associacoes':
        $item4 = "Declaração de Associação sem fins lucrativos.";
        break;
}

$dataAtual = dataHoraNow();
session_start();

// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI ".$pedido['numero_processo']." - Email". ucfirst($modeloEmail) .".doc");
?>
<html lang="pt-br">
<meta http-equiv="Content-Language" content="pt-br">
<!-- HTML 4 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
<body>
<p align="justify">Prezado(a) Senhor(a) <?= $pedido['nome'] ?>,</p>
<p align="justify">Tendo em vista a apresentação <?= $pedido['nome_evento'] ?>, na data/período <?= $periodo ?>, encaminho em anexo, para fins de pagamento, os itens abaixo relacionados:</p>
<p align="justify">a) Recibo da nota de empenho (para ser assinado pelo(a) representante legal da Empresa);</p>
<p align="justify">b) Pedido de pagamento (para ser assinado pelo(a) representante legal);</p>
<p align="justify">c) Instruções para Emissão da Nota Fiscal Eletrônica;</p>
<p align="justify">d) <?=$item4?></p>
<p align="justify">Para fins de arquivamento da empresa, segue também o Anexo e a Nota de Empenho da referida contratação.</p>
<p align="justify">Informo que a documentação acima citada deverá ser devolvida digitalizada, <strong>somente através do e-mail smc.pagamentosartisticos@gmail.com, em até 48 horas, impreterivelmente.</strong></p>
<p>&nbsp;</p>
<p align="justify">Atenciosamente,</p>
<br>
<p><?=$_SESSION['nome']?><br>
    SMC / Pagamentos Artísticos<br>
    Tel: (11) 3397-0191</p>
</body>
</html>