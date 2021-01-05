<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT e.id AS idEvento, p.numero_processo, pj.razao_social, pj.ccm, pj.cnpj, pe.logradouro, pe.numero, pe.complemento, pe.bairro, pe.cidade, pe.uf, pe.cep, pj.email, pj.id AS idPj, e.nome_evento, p.valor_total, pj.representante_legal1_id, pj.representante_legal2_id
FROM pedidos AS p 
    INNER JOIN eventos AS e ON p.origem_id = e.id 
    INNER JOIN pessoa_juridicas pj on p.pessoa_juridica_id = pj.id
    INNER JOIN pj_enderecos pe on pj.id = pe.pessoa_juridica_id
WHERE p.publicado = 1 AND e.publicado = 1 AND p.origem_tipo_id = 1 AND p.id = '$idPedido'")->fetch_assoc();

$parcela = $con->query("SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1");
if($parcela->num_rows == 0){
    $valor = $pedido['valor_total'];
} else {
    $idParcela = $parcela->fetch_array()['id'];
    $parc = $con->query("SELECT valor FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1 AND id = '$idParcela'")->fetch_assoc();
    $valor = $parc['valor'];
}

$idPj = $pedido['idPj'];
$telefones = $con->query("SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPj'");

$idRep1 = $pedido['representante_legal1_id'];
$rep1 = $con->query("SELECT nome, rg FROM representante_legais WHERE id = '$idRep1'")->fetch_assoc();

$idRep2 = $pedido['representante_legal2_id'];
$rep2 = $con->query("SELECT nome, rg FROM representante_legais WHERE id = '$idRep2'")->fetch_assoc();

$now = dataHoraNow();
$processo = $pedido['numero_processo'];

// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$now - Processo SEI $processo - Pedido de Pagamento.doc");
?>

<html lang="pt-br">
<meta http-equiv="Content-Language" content="pt-br">
<!-- HTML 4 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
<body>

<p align="center"><strong>PEDIDO DE PAGAMENTO DE PESSOA JURÍDICA</strong></p>
<p>&nbsp;</p>
<p><strong>
	Senhor(a) Diretor(a)<br>
	Secretaria Municipal de Cultura
</strong></p>
<p>&nbsp;</p>
<p><strong>Nome da empresa:</strong> <?= $pedido['razao_social'] ?><br>
    <strong>CCM:</strong> <?= $pedido['ccm'] == null ? "Não cadastado" : $pedido['ccm'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CNPJ:</strong> <?= $pedido['cnpj'] ?><br>
    <strong>Endereço:</strong> <?= $pedido['logradouro'].", ".$pedido['numero']." ".$pedido['complemento']." ".$pedido['bairro']." - ".$pedido['cidade']." - ".$pedido['uf']." CEP: ".$pedido['cep'] ?><br>
    <strong>Telefone:</strong>
    <?php
    while ($telefone = mysqli_fetch_array($telefones)){
        echo $telefone['telefone']. " | ";
    }
        ?><br>
    <strong>E-mail:</strong> <?= $pedido['email'] ?>
</p>
<p>&nbsp;</p>
<p><strong>Evento:</strong> <?= $pedido['nome_evento'] ?><br>
    <strong>Data / Período:</strong> <?= retornaPeriodo($pedido['idEvento']) ?><br>
    <strong>Local:</strong> <?php retornaLocal($pedido['idEvento']) ?><br>
    <strong>Valor:</strong> R$ <?= dinheiroParaBr($valor) ?> ( <?= valorPorExtenso($valor)?> )
</p>
<p>&nbsp;</p>
<p>Venho, mui respeitosamente, requerer que o(a) senhor(a) se digne submeter a exame à decisão do órgão competente o pedido supra.</p>
<p>Declaro, sob as penas da Lei, não possuir débitos perante as Fazendas Públicas, em especial com a Prefeitura do Município de São Paulo. Nestes termos, encaminho para deferimento.</p>
<p>&nbsp;</p>
<p>São Paulo, _______ de ________________________ de <?= date('Y') ?>.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>________________________________________________<br>
    <?= $rep1['nome'] ?><br/>
	RG: <?= $rep1['rg'] ?></p>
<?php
if($rep2 != NULL){
    ?>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>________________________________________________<br>
        <?= $rep2['nome'] ?><br/>
        RG: <?= $rep2['rg'] ?></p>
<?php
}
?>
</body>
</html>