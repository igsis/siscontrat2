<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT p.numero_processo, p.pessoa_juridica_id, e.nome_evento, e.id AS idEvento, pj.razao_social, pj.cnpj, pe.logradouro, pe.numero, pe.complemento, pe.bairro, pe.cidade, pe.uf, pe.cep, rl.nome, rl.cpf, rl.rg, p.valor_total, p.numero_parcelas, uf.nome_completo AS fiscal_nome, uf.rf_rg AS fiscal_rf, us.nome_completo AS suplente_nome, us.rf_rg AS suplente_rf
FROM pedidos AS p 
INNER JOIN eventos as e ON p.origem_id = e.id
INNER JOIN pessoa_juridicas pj on p.pessoa_juridica_id = pj.id
INNER JOIN pj_enderecos pe on pj.id = pe.pessoa_juridica_id
INNER JOIN representante_legais rl on pj.representante_legal1_id = rl.id
LEFT JOIN usuarios uf on e.fiscal_id = uf.id
LEFT JOIN usuarios us on e.suplente_id = us.id
WHERE p.publicado = 1 AND e.publicado = 1 AND p.origem_tipo_id = 1 AND p.id = '$idPedido'
")->fetch_assoc();

$periodo = retornaPeriodo($pedido['idEvento']);

$lider = $con->query("SELECT pf.nome FROM lideres l INNER JOIN pessoa_fisicas pf on l.pessoa_fisica_id = pf.id WHERE l.pedido_id='$idPedido'")->fetch_assoc()['nome'];

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=minuta_176k.doc");
?>

<html lang="pt-br">
<meta http-equiv="Content-Language" content="pt-br">
<!-- HTML 4 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
<body>
<style type='text/css'>
.style_01 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
</style>

<p><strong>PREFEITURA DO MUNICÍPIO DE SÃO PAULO</strong><br>
    <strong>SECRETARIA MUNICIPAL DE CULTURA</strong></p>
<p><strong>TERMO DE CONTRATO Nº _______________________</strong></p>
<p><strong>Processo nº <?php echo strtoupper($pedido['numero_processo']); ?></strong></p>
<p><strong>TERMO DE CONTRATO DE PRESTAÇÃO DE SERVIÇOS FORMALIZADO ENTRE A SECRETARIA MUNICIPAL CULTURA E <?= strtoupper($pedido['razao_social']); ?>, COM FUNDAMENTO NO ARTIGO 25, INCISO III, DA LEI FEDERAL Nº 8666/93 E ALTERAÇÕES POSTERIORES, ARTIGO 1º DA LEI MUNICIPAL Nº 13.278/02 E ARTIGOS 16 E 17 DO DECRETO MUNICIPAL Nº 44.279/03.</strong></p>
<p> A PREFEITURA DO MUNICÍPIO DE SÃO PAULO doravante denominada simplesmente PREFEITURA, através da SECRETARIA MUNICIPAL DE CULTURA, neste ato representada pela Chefe de Gabinete, Carla Mingolla, e <?= strtoupper($pedido['razao_social']); ?>, CNPJ <?= $pedido['cnpj'] ?>, com endereço <?= strtoupper($pedido['logradouro'].", ".$pedido['numero']." ".$pedido['complemento']." ".$pedido['bairro']. " - ".$pedido['cidade']." - ".$pedido['uf']. " CEP:".$pedido['cep']); ?> , neste ato representada por <?= strtoupper($pedido['nome']); ?>, RG n° <?= strtoupper($pedido['rg']); ?> , CPF Nº <?= $pedido['cpf'] ?>, doravante denominada CONTRATADA, com fundamento no artigo 25, inciso III da Lei Federal nº 8.666/93 e conforme consta do processo administrativo em referência, tem justo e acordado o que segue:</p>
<p>&nbsp;</p>
<h3>CLÁUSULA PRIMEIRA - DO OBJETO</h3>
<p> </p>
<p>Contratação dos serviços profissionais de natureza artística de <?= strtoupper($pedido['nome_evento']); ?>, através de <?php echo strtoupper($lider); ?> e demais integrantes mencionados na Declaração de Exclusividade, por intermédio da empresa <?= strtoupper($pedido['razao_social']); ?>, CNPJ: <?= ($pedido['cnpj']); ?>, representada legalmente por <?= strtoupper($pedido['nome']); ?>, CPF: <?= $pedido['cpf']; ?>, para realização do <?= $pedido['nome_evento']; ?> no <?php retornaLocal($pedido['idEvento']); ?>, no período <?= ($periodo); ?>, conforme proposta e cronograma.</p>
<p> &nbsp; </p>
<h3>CLÁUSULA SEGUNDA – DAS CONDIÇÕES GERAIS</h3>
<p> </p>
<p>2.1 O presente contrato é regido pelas leis e normas vigentes, especialmente pela Lei Federal nº. 8.666/93, artigo 1º. da Lei Municipal nº. 13.278/02 nos termos dos artigos 16 e 17 do Decreto nº. 44.279/03, inclusive quanto às hipóteses de rescisão.</p>
<p>2.2 A CONTRATANTE se exime de todo e quaisquer ônus e obrigações assumidas pela CONTRATADA em decorrência de eventual contratação de terceiros.</p>
<p>2.3 A CONTRATANTE fica inteiramente responsável por garantir as condições indispensáveis à consecução dos trabalhos por parte da CONTRATADA no local e horários estipulados.</p>
<p>&nbsp;</p>
<h3>CLÁUSULA TERCEIRA – DO PREÇO E CONDIÇÕES DE PAGAMENTO</h3>
<p> </p>
<p>3.1 Pelos serviços prestados, a CONTRATANTE pagará à CONTRATADA o total de R$ <?= dinheiroParaBr($pedido['valor_total']) ?> ( <?= valorPorExtenso($pedido['valor_total']) ?> ), a serem pagos em <?= $pedido['numero_parcelas'] ?> parcelas, após a confirmação da execução dos serviços pela unidade requisitante.</p>
<p>3.2  As despesas relativas ao presente Contrato estão garantidas pela dotação n° 25.10 13.392.3001.6.354 3.3.90.39.00.00. </p>
<p>3.3 Não haverá reajuste do valor contratual.</p>
<p>3.4 No caso de atraso no pagamento por culpa exclusiva da CONTRATANTE haverá, a pedido da CONTRATADA, compensação financeira, nos termos da Portaria SF nº. 05, publicada em 07 de janeiro de 2012. </p>
<p>&nbsp;</p>
<h3>CLÁUSULA QUARTA – DA RESCISÃO E PENALIDADES</h3>
<p> </p>
<p>4.1  A CONTRATADA incorrerá em multa de:</p>
<p>4.1.1. 10% (dez por cento) no caso de infração de cláusula contratual, desobediência às determinações da fiscalização ou se desrespeitar munícipes ou funcionários municipais;</p>
<p>4.1.2 10% (dez por cento) no caso de inexecução parcial do contrato;</p>
<p>4.1.3 30% (trinta por cento) no caso de inexecução total do contrato;</p>
<p>4.1.4 10% (dez por cento) a cada 30 (trinta) minutos de atraso no início do evento sobre o valor total do ajuste. Ultrapassado esse tempo, e independentemente da aplicação da penalidade, fica a critério da SMC autorizar a realização do evento, visando evitar prejuízos à grade de programação. Não sendo autorizada a realização do evento, será considerada inexecução parcial ou total do ajuste conforme o caso, com aplicação da multa prevista por inexecução, acumulada da multa de 20% (vinte por cento) do valor do contrato por rescisão contratual por culpa do contratado. </p>
<p>4.1.5. 10% (dez por cento) sobre o valor do contrato, em função da falta de regularidade fiscal da Contratada, bem como, pela verificação de que a Contratada possui pendências junto ao Cadastro Informativo Municipal – CADIN MUNICIPAL.</p>
<p>4.2 O valor da multa será calculado sobre o valor total do contrato.</p>
<p>4.3. A multa será descontada do pagamento devido ou será inscrita como divida ativa, sujeita à cobrança judicial.</p>
<p>4.4 As multas são independentes entre si, podendo ser aplicadas conjuntamente.</p>
<p>4.5. Além da pena de multa poderá a contratada ser apenada com suspensão temporária de contratar e licitar com a Municipalidade, de acordo com a legislação aplicável.</p>
<p>4.6. O contrato será rescindido nos casos previstos em lei.</p>
<p>&nbsp;</p>
<h3>CLÁUSULA QUINTA – DAS DISPOSIÇÕES FINAIS</h3>
<p>5.1 Nos termos do art. 6º do Decreto Municipal nº 54.873/2014, designo como fiscal desta contratação artística o(a) servidor(a) <?= strtoupper($pedido['fiscal_nome']); ?> , RF: <?= strtoupper($pedido['fiscal_rf']); ?> e, como substituto,  <?= strtoupper($pedido['suplente_nome']); ?>, RF: <?php echo strtoupper($pedido['suplente_rf']); ?>.</p>
<p>5.2 O Foro da Fazenda Pública desta Capital será o competente para todo e qualquer procedimento oriundo deste contrato, com renúncia de qualquer outro, por mais especial e privilegiado que seja.</p>
<p>E, para constar, o presente Termo foi digitado em três vias, de igual teor, o qual lido e achado conforme vai assinado pelas partes, com as testemunhas abaixo a tudo presentes.</p>

<p>&nbsp;</p>

<p align='center'>São Paulo, _________ de ________________________________ de <?= date('Y') ?>.</p>

<p>&nbsp;</p>
<p>&nbsp;</p>

<p align='center'><strong>____________________________________<br/>
CARLA MINGOLLA<br/>
Chefe de Gabinete<br/>
Secretaria Municipal de Cultura
</strong></p>

<p>&nbsp;</p>

<p align='center'><strong>________________________________<br/>
<?= $pedido['razao_social']; ?><br/>
CNPJ: <?= $pedido['cnpj'] ?>
    </strong></p>

<p>&nbsp;</p>

<p align='center'><strong>TESTEMUNHAS:</strong></p>
<p>&nbsp;</p>
<p>&nbsp;</p>


<p align='center'>_______________________________ &nbsp; &nbsp; &nbsp; _______________________________ </p>

<p>&nbsp;</p>

</body>
</html>
