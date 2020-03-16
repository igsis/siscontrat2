<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, p.numero_processo, e.nome_evento, uf.nome_completo AS fiscal_nome, uf.rf_rg AS fiscal_rf, us.nome_completo AS suplente_nome, us.rf_rg AS suplente_rf, e.id AS idEvento
FROM pedidos AS p 
INNER JOIN eventos as e ON p.origem_id = e.id
LEFT JOIN usuarios uf on e.fiscal_id = uf.id
LEFT JOIN usuarios us on e.suplente_id = us.id
WHERE p.publicado = 1 AND e.publicado = 1 AND p.origem_tipo_id = 1 AND p.id = '$idPedido'
")->fetch_assoc();

if($pedido['pessoa_tipo_id'] == 1){
    $idPf = $pedido['pessoa_fisica_id'];
    $proponente = $con->query("SELECT nome FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_assoc()['nome'];
} else{
    $idPj = $pedido['pessoa_juridica_id'];
    $proponente = $con->query("SELECT razao_social FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_assoc()['razao_social'];
}

$periodo = retornaPeriodoNovo($pedido['idEvento'], 'ocorrencias');
$tudo = retornaDiasOcorrencias($pedido['idEvento']);
?>
 
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html. charset=Windows-1252">

<style>
.texto{
 	width: 900px;
 	border: solid;
 	padding: 20px;
 	font-size: 12px;
 	font-family: Arial, Helvetica, sans-serif;
	text-align:justify;
}
</style>
<script src="include/dist/ZeroClipboard.min.js"></script>
</head>

<body>
  
<?php
$sei = 
  "<p><strong><u><center>ATESTADO DE CONFIRMAÇÃO DE SERVIÇOS</strong></p></u></center>".
  "<p>&nbsp;</p>".
  "<p>Informamos que os serviços prestados por: ".$proponente." </p>".
  "<p>&nbsp;</p>".
  "<p><strong>Processo: </strong> ".$pedido['numero_processo']." </p>".
  "<p><strong>Evento: </strong> ".$pedido['nome_evento']." </p>".
  "<p><strong>Período: </strong> ".$periodo."</p>".
  "<p>&nbsp;</p>".
  "<p>( X ) FORAM REALIZADOS A CONTENTO</p>".
  "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS</p>".
  "<p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) NÃO FORAM REALIZADOS A CONTENTO, PELO SEGUINTE MOTIVO:</p>".
  "<p>&nbsp;</p>".
  "<p><strong>DADOS DO SERVIDOR (A) QUE ESTÁ CONFIRMANDO OU NÃO A REALIZAÇÃO DOS SERVIÇOS:</strong></p>".
  "<p><strong>FISCAL: </strong> ".$pedido['fiscal_nome']."</p>".
  "<p><strong>RF: </strong>".$pedido['fiscal_rf']."</p>".
  "<p><strong>SUPLENTE: </strong> ".$pedido['suplente_nome']."</p>".
  "<p><strong>RF: </strong>".$pedido['suplente_rf']."</p>".
  "<p>&nbsp;</p>".
  "<p>Atesto que os serviços prestados discriminados no documento:<strong> LINK NOTA FISCAL OU RECIBO DE PAGAMENTO</strong>, foram executados a contento nos termos previstos no instrumento contracontratual (ou documento equivalente) nos dias:</p>".
  "<p>&nbsp;</p>".
  "<p>&nbsp;</p>".
  "<p>Dentro do prazo previsto.</p>".
  "<p>O prazo contratual é ".$periodo."</p>".
  "<p>À área gestora de liquidação e pagamento encaminho para prosseguimento.</p>"
?>

<div align="center">
    <div id="texto" class="texto"><?php echo $sei; ?></div>
</div>

<p>&nbsp;</p>

<div align="center"><button id="botao-copiar" data-clipboard-target="texto"><img src="img/copy-icon.jpg"> CLIQUE AQUI PARA COPIAR O TEXTO</button>
    <a href="http://sei.prefeitura.sp.gov.br" target="_blank">
        <button>CLIQUE AQUI PARA ACESSAR O <img src="img/sei.jpg"></button>
    </a>
</div>

<script>
    var client = new ZeroClipboard();
    client.clip(document.getElementById("botao-copiar"));
    client.on("aftercopy", function(){
        alert("Copiado com sucesso!");
    });
</script>

</body>
</html>