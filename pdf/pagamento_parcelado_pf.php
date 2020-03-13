<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT e.id AS idEvento, p.numero_processo, pf.nome, pf.nome_artistico, pf.passaporte,n.nacionalidade, pf.ccm, pf.rg, pf.cpf, d.drt, pe.logradouro, pe.numero, pe.complemento, pe.bairro, pe.cidade, pe.uf, pe.cep, pf.email, n2.nit, pf.data_nascimento, pf.id AS idPf, e.nome_evento, p.valor_total
FROM pedidos AS p 
    INNER JOIN eventos AS e ON p.origem_id = e.id 
    INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id 
    INNER JOIN nacionalidades n on pf.nacionalidade_id = n.id 
    LEFT JOIN drts d on pf.id = d.pessoa_fisica_id
    INNER JOIN pf_enderecos pe on pf.id = pe.pessoa_fisica_id
    LEFT JOIN nits n2 on pf.id = n2.pessoa_fisica_id
WHERE p.publicado = 1 AND e.publicado = 1 AND p.origem_tipo_id = 1 AND p.id = '$idPedido'")->fetch_assoc();

$idPf = $pedido['idPf'];
$telefones = $con->query("SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'");

$parcela = $con->query("SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1");


$parcela = $con->query("SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1")->fetch_array();
if($parcela['id'] == NULL){
    $valor = $pedido['valor_total'];
} else {
    $idParcela = $parcela['id'];
    $parc = $con->query("SELECT valor FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1 AND id = '$idParcela'")->fetch_assoc();
    $valor = $parc['valor'];
}

$now = dataHoraNow();
$processo = $pedido['numero_processo'];


if($pedido['passaporte'] != NULL){
    $trecho_passaporte = "<strong>Passaporte:</strong> " . $pedido['passaporte'] . "&nbsp;&nbsp;&nbsp;&nbsp;<strong>DRT:</strong> . " . $pedido['drt'] . "<br>";
    $label = "Passaporte: " . $pedido['passaporte'] . "</p>";
}else{
    $trecho_passaporte = "<strong>RG:</strong> " . $pedido['rg'] . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CPF:</strong> " . $pedido['cpf'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>DRT:</strong> . " . $pedido['drt'] . "<br>";
    $label = "RG: " . $pedido['rg'] . " <br/>
	          CPF:" . $pedido['cpf'] . "</p>";
}

// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$now - Processo SEI $processo - Parcela  $now.doc");
?>

<html lang="pt-br">
<meta http-equiv="Content-Language" content="pt-br">
<!-- HTML 4 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
<body>

<p align="center"><strong>PEDIDO DE PAGAMENTO DE PESSOA FÍSICA</strong></p>
<p>&nbsp;</p>
<p><strong>
        Senhor(a) Diretor(a) Secretaria Municipal de Cultura
    </strong></p>
<p>&nbsp;</p>
<p><strong>Nome:</strong> <?= $pedido['nome'] ?><br>
    <strong>CCM:</strong> <?= $pedido['ccm'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CPF: <?=$pedido['cpf']?> </strong><br>
    <strong>Endereço:</strong> <?= $pedido['logradouro'].", ".$pedido['numero']." ".$pedido['complemento']." ".$pedido['bairro']." - ".$pedido['cidade']." - ".$pedido['uf']." CEP: ".$pedido['cep'] ?><br>
    <strong>Telefone:</strong>
    <?php
    while ($telefone = mysqli_fetch_array($telefones)){
        echo $telefone['telefone']. " | ";
    }
    ?><br>
    <strong>E-mail:</strong> <?= $pedido['email'] ?><br>
</p>
<p>&nbsp;</p>
<p><strong>Evento:</strong> <?= $pedido['nome_evento'] ?><br>
    <strong>Data / Período:</strong> <?= retornaPeriodo($pedido['idEvento']) ?><br>
    <strong>Local:</strong> <?php retornaLocal($pedido['idEvento']) ?><br>
    <strong>Valor:</strong> R$ <?= dinheiroParaBr($valor) ?> ( <?= valorPorExtenso($valor)?> )
</p>
<p>&nbsp;</p>
<p style="text-align: justify;">Venho, mui respeitosamente, requerer  que o(a) senhor(a) se digne  submeter a exame   à  decisão do órgão competente o pedido supra.</p>
<p style="text-align: justify;">Declaro, sob as penas da Lei, não possuir débitos perante as Fazendas Públicas, em especial com a Prefeitura do Município de São Paulo.
    Nestes termos, encaminho para deferimento.</p>
<p>&nbsp;</p>
<p style="text-align: justify;">São Paulo, _______ de ________________________ de <?= date('Y') ?>.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>________________________________________________<br><br>
    <?= $pedido['nome'] ?><br/>
    <?= $label ?>
</body>
</html>