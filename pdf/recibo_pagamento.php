<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA 
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT p.numero_processo, p.valor_total, e.nome_evento, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.id AS idEvento
    FROM pedidos p 
    INNER JOIN eventos as e ON p.origem_id = e.id
    WHERE p.publicado = 1 AND e.publicado = 1 AND p.origem_tipo_id = 1 AND p.id = '$idPedido'
")->fetch_assoc();

$periodo = retornaPeriodo($pedido['idEvento']);

$dataAtual = dataHoraNow();

// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI ".$pedido['numero_processo'].".doc");
?>

<html lang="pt-br">
<meta http-equiv="Content-Language" content="pt-br">
<!-- HTML 4 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
<body>

<p align="center"><strong>RECIBO DE PAGAMENTO</strong></p>
<p>&nbsp;</p>
<p align="justify">Recebi da Prefeitura de São Paulo - Secretaria Municipal de Cultura a importância de R$ <?= dinheiroParaBr($pedido['valor_total']) ?> ( <?= valorPorExtenso($pedido['valor_total']) ?> ) referente à serviços prestados por meio do Processo Administrativo <?= $pedido['numero_processo'] ?>.</p>
<?php
if($pedido['pessoa_tipo_id'] == 1){
    $idPf = $pedido['pessoa_fisica_id'];
    $pf = $con->query("SELECT pf.nome, pf.ccm, pf.cpf, pf.rg, pf.email, pf.data_nascimento, n.nacionalidade, pe.logradouro, pe.numero, pe.complemento, pe.bairro, pe.cidade, pe.uf, pe.cep, n2.nit
        FROM pessoa_fisicas pf 
            INNER JOIN nacionalidades n on pf.nacionalidade_id = n.id 
            INNER JOIN pf_enderecos pe on pf.id = pe.pessoa_fisica_id 
            LEFT JOIN nits n2 on pf.id = n2.pessoa_fisica_id
        WHERE pf.id = '$idPf'")->fetch_assoc();
    $telefones = $con->query("SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'");
    ?>
    <p align="justify"><strong>Nome:</strong> <?= $pf['nome'] ?></p>
    <p><strong>Nacionalidade:</strong> <?= $pf['nacionalidade'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CCM:</strong> <?= $pf['ccm'] ?></p>
    <p><strong>RG:</strong> <?= $pf['rg'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CPF:</strong> <?= $pf['cpf'] ?></p>
    <p><strong>Endereço:</strong> <?= $pf['logradouro'].", ".$pf['numero']." ".$pf['complemento']." ".$pf['bairro']." - ".$pf['cidade']." - ".$pf['uf']." CEP: ".$pf['cep'] ?></p>
    <p><strong>Telefone:</strong>
        <?php while ($telefone = mysqli_fetch_array($telefones)){
            echo $telefone['telefone']. " | ";
        } ?></p>
    <p><strong>E-mail:</strong> <?= $pf['email'] ?></p>
    <p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> <?= $pf['nit'] ?></p>
    <p><strong>Data de Nascimento:</strong> <?= exibirDataBr($pf['data_nascimento']) ?></p>
<?php
} else{
    $idPj = $pedido['pessoa_juridica_id'];
    $pj = $con->query("SELECT pj.razao_social, pj.ccm, pj.cnpj, pj.email, pe.logradouro, pe.numero, pe.complemento, pe.bairro, pe.cidade, pe.uf, pe.cep, rl1.nome r1_nome, rl1.rg r1_rg, rl2.nome r2_nome, rl2.rg r2_rg 
        FROM pessoa_juridicas pj 
            INNER JOIN pj_enderecos pe on pj.id = pe.pessoa_juridica_id 
            INNER JOIN representante_legais rl1 on pj.representante_legal1_id = rl1.id
            LEFT JOIN representante_legais rl2 on pj.representante_legal2_id = rl2.id
        WHERE pj.id = '$idPj'")->fetch_assoc();
    $telefones = $con->query("SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPj'");
    ?>
    <p align="justify"><strong>Nome da empresa:</strong> <?= $pj['razao_social'] ?></p>
    <p><strong>CCM:</strong> <?= $pj['ccm'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CNPJ:</strong> <?= $pj['cnpj'] ?></p>
    <p><strong>Endereço:</strong> <?= $pj['logradouro'].", ".$pj['numero']." ".$pj['complemento']." ".$pj['bairro']." - ".$pj['cidade']." - ".$pj['uf']." CEP: ".$pj['cep'] ?></p>
    <p><strong>Telefone:</strong>
        <?php while ($telefone = mysqli_fetch_array($telefones)){
            echo $telefone['telefone']. " | ";
        } ?></p>
    <p><strong>E-mail:</strong> <?= $pj['email'] ?></p>
<?php
}
?>
<p align="justify"><strong>Serviço Prestado:</strong> <?= $pedido['nome_evento'] ?></p>
<p align="justify"><strong>Data / Período:</strong> <?= $periodo?></p>
<p align="justify"><strong>Local:</strong> <?php retornaLocal($pedido['idEvento']) ?></p>
<p>&nbsp;</p>
<p align="justify">São Paulo, _______ de ________________________ de <?= date('Y') ?>.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
if($pedido['pessoa_tipo_id'] == 1){
    ?>
    <p>________________________________________________<br>
        <?= $pf['nome'] ?><br>
        RG: <?= $pf['rg'] ?> <br>
        CPF: <?= $pf['cpf'] ?></p>
    <?php
} else{
    ?>
    <p>________________________________________________<br>
        <?= $pj['r1_nome'] ?><br>
        RG: <?= $pj['r1_rg'] ?></p>
    <?php
    if($pj['r2_nome'] != NULL){
        ?>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>________________________________________________<br>
            <?= $pj['r2_nome'] ?><br>
            RG: <?= $pj['r2_rg'] ?></p>
        <?php
    }
}
?>

<p align="justify"><strong>OBSERVAÇÃO:</strong> A validade deste recibo fica condicionada ao efetivo por ordem de pagamento ou depósito na conta corrente no Banco do Brasil, indicada pelo contratado, ou na falta deste, ao recebimento no Departamento do Tesouro da Secretaria das Finanças e Desenvolvimento Econômico, situado à Rua Pedro Américo, 32.</p>

</body>
</html>