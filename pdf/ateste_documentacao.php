<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, pg.nota_empenho, e.id AS idEvento
FROM pedidos AS p 
INNER JOIN eventos as e ON p.origem_id = e.id
LEFT JOIN pagamentos pg on p.id = pg.pedido_id
WHERE p.publicado = 1 AND e.publicado = 1 AND p.origem_tipo_id = 1 AND p.id = '$idPedido'
")->fetch_assoc();

if($pedido['pessoa_tipo_id'] == 1){
    $idPf = $pedido['pessoa_fisica_id'];
    $proponente = $con->query("SELECT nome FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_assoc()['nome'];
} else{
    $idPj = $pedido['pessoa_juridica_id'];
    $proponente = $con->query("SELECT razao_social FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_assoc()['razao_social'];
}

$idEvento = $pedido['idEvento'];

$norte = $con->query("SELECT SUM(ve.valor) AS valor
    FROM ocorrencias AS o
    INNER JOIN locais l on o.local_id = l.id
    LEFT JOIN valor_equipamentos ve on l.id = ve.local_id
    WHERE o.publicado = 1 AND l.publicado = 1 AND o.origem_ocorrencia_id = '$idEvento' AND l.zona_id = 1")->fetch_assoc()['valor'];
$sul = $con->query("SELECT SUM(ve.valor) AS valor
    FROM ocorrencias AS o
    INNER JOIN locais l on o.local_id = l.id
    LEFT JOIN valor_equipamentos ve on l.id = ve.local_id
    WHERE o.publicado = 1 AND l.publicado = 1 AND o.origem_ocorrencia_id = '$idEvento' AND l.zona_id = 2")->fetch_assoc()['valor'];
$leste = $con->query("SELECT SUM(ve.valor) AS valor
    FROM ocorrencias AS o
    INNER JOIN locais l on o.local_id = l.id
    LEFT JOIN valor_equipamentos ve on l.id = ve.local_id
    WHERE o.publicado = 1 AND l.publicado = 1 AND o.origem_ocorrencia_id = '$idEvento' AND l.zona_id = 3")->fetch_assoc()['valor'];
$oeste = $con->query("SELECT SUM(ve.valor) AS valor
    FROM ocorrencias AS o
    INNER JOIN locais l on o.local_id = l.id
    LEFT JOIN valor_equipamentos ve on l.id = ve.local_id
    WHERE o.publicado = 1 AND l.publicado = 1 AND o.origem_ocorrencia_id = '$idEvento' AND l.zona_id = 4")->fetch_assoc()['valor'];
$centro = $con->query("SELECT SUM(ve.valor) AS valor
    FROM ocorrencias AS o
    INNER JOIN locais l on o.local_id = l.id
    LEFT JOIN valor_equipamentos ve on l.id = ve.local_id
    WHERE o.publicado = 1 AND l.publicado = 1 AND o.origem_ocorrencia_id = '$idEvento' AND l.zona_id = 5")->fetch_assoc()['valor'];
$valores= "";
$texto = "";

if($norte != NULL){
    $valores = "a região norte no valor de R$ ".dinheiroParaBr($norte)." ( ".valorPorExtenso($norte)." )";
}

if($sul != NULL){
    $valores .= ", a região sul no valor de R$ ".dinheiroParaBr($sul)." ( ".valorPorExtenso($sul)." )";
}

if($leste != NULL){
    $valores .= ", a região leste no valor de R$ ".dinheiroParaBr($leste)." ( ".valorPorExtenso($leste)." )";
}

if($oeste != NULL){
    $valores .= ", a região oeste no valor de R$ ".dinheiroParaBr($oeste)." ( ".valorPorExtenso($oeste)." )";
}

if($centro != NULL){
    $valores .= ", a região centro no valor de R$ ".dinheiroParaBr($centro)." (".valorPorExtenso($centro)." )";
}

if($norte != "0,00" || $sul != "0,00" || $leste != "0,00" || $oeste != "0,00" || $centro != "0,00"){
    $texto = "<p>&nbsp;</p><p>Em atendimento ao item referente a regionalização e georreferenciamento das despesas municipais com a implantação do detalhamento da ação, informo que a despesa aqui tratada se refere(m) ".$valores.".</p>";
}
?>
 
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html. charset=Windows-1252">
<style>
.texto{
 	width: 900px;
 	border: solid;
 	padding: 20px;
 	font-size: 13px;
 	font-family: Arial, Helvetica, sans-serif;
	text-align:justify;
}
</style>
    <link rel="stylesheet" href="../visual/css/bootstrap.min.css">
    <link rel="stylesheet" href="../visual/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  
<?php
$sei = 
  "<p><strong>Interessado:</strong> ".$proponente."</p>".
  "<p><strong>Do evento:</strong> ".$pedido['nome_evento']."</p>".
  "<p>&nbsp;</p>".
  "<p>Atesto o recebimento em <strong>DATA</strong>, de toda a documentação: nota fiscal  <strong>LINK NOTA FISCAL</strong> e arquivos consolidados, previstos na Portaria SF 08/16.</p>".
  "<p>&nbsp;</p>".
  "<p>&nbsp;</p>".
  "<p><strong>SMC - CONTABILIDADE</strong></p>".
  "<p><strong>Sr.(a) Contador(a)</strong></p>".
  "<p>&nbsp;</p>". 
  "<p>Encaminho o presente para providências quanto ao pagamento, uma vez que os serviços foram realizados e confirmados a contento conforme documento <strong>LINK DA SOLICITAÇÃO</strong>.</p>".
  $texto.
  "<p>&nbsp;</p>".
  "<p>&nbsp;</p>".
  "<p>INFORMAÇÕES COMPLEMENTARES</p>".
  "<hr>".  
  "<p><strong>Nota e Anexo de Empenho: </strong>".$pedido['nota_empenho']."</p>".
  "<p><strong>Kit de Pagamento Assinado:</strong></p>".
  "<p><strong>Certdões Fiscais:</strong></p>".
  "<p><strong>FACC:</strong></p>"
?>

<div align="center">
    <div id="texto" class="texto"><?= $sei; ?></div>
</div>

<p>&nbsp;</p>

<div align="center">
    <button id="botao-copiar" class="btn btn-primary"><i class="fa fa-copy"></i> CLIQUE AQUI PARA COPIAR O TEXTO </button>
    <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
        <button class="btn btn-primary">CLIQUE AQUI PARA ACESSAR O <img src="../visual/images/logo_sei.jpg"></button>
    </a>
</div>


</body>
</html>