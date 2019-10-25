<?php
//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


//CONSULTA
$idPedido = $_POST['idPedido'];


$pedido = recuperaDados('pedidos','id',$idPedido);
$evento = recuperaDados('eventos','id', $pedido['origem_id']);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
$ocorrencia = recuperaDados('ocorrencias','origem_ocorrencia_id', $evento['id']);
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$sqlLocal = "SELECT local FROM locais WHERE id = " . $ocorrencia['local_id'];
$locais = $con->query($sqlLocal)->fetch_array();
$sqlEnvio = "SELECT data_envio FROM  evento_envios WHERE evento_id =" . $ocorrencia['origem_id'];
$dia = $con->query($sqlEnvio)->fetch_array();

$nome = $pessoa['nome'];
$cpf = $pessoa['cpf'];


$local = $locais['local'];
$periodo = retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias');
$valor = $pedido['valor_total'];
$valor_extenso = valorPorExtenso($valor);
$forma_pag = $pedido['forma_pagamento'];

$fiscal = recuperaDados('usuarios', 'id', $pedido['fiscal_id']);
$fiscalNome = $fiscal['nome_completo'];
$fiscalRF = $fiscal['rf_rg'];

$suplente = recuperaDados('usuarios', 'id', $pedido['suplente_id'])['nome_completo'];
$suplenteNome = $suplente['nome_completo'];
$suplenteRF = $suplente['rf_rg'];


$ano = date('Y');
$dataAtual = date("d/m/Y");

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=. " .  $pedido['numero_processo'] . " em $dataAtual.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";

echo "<p align='justify'><b>CONTRATANTE:</b> "."SMC"."</p>";
echo "<p align='justify'><b>CONTRATADO(S):</b> Contratação de <b>"."$nome"."</b>, CPF "."$cpf"." e demais integrantes relacionados na declaração de exclusividade.</p>";
echo "<p align='justify'><b>EVENTO/SERV:</b> Apresentação do "."$objeto".", conforme segue:<br>
"."$local"." <br></p>";
echo "<p align='justify'><b>DATA/PERÍODO: </b>"."$periodo".".</p>";
echo "<p align='justify'><b>VALOR TOTAL DA CONTRATAÇÃO:</b> "."R$ $valor"."  "."($valor_extenso)"."<br> Quaisquer despesas aqui não ressalvadas, bem como direitos autorais, serão de responsabilidade do(a) contratado(a).</p>";
echo "<p align='justify'><b>CONDIÇÕES DE PAGAMENTO: </b>"."$forma_pag".".</p>";
echo "<p align='justify'>O pagamento será efetuado por crédito em conta corrente no BANCO DO BRASIL, em  conformidade com o Decreto 51.197/2010, publicado no DOC de 23.01.2010.<br/>
De acordo com a Portaria nº 5/2012 de SF, haverá compensação financeira, se houver atraso no pagamento do valor devido, por culpa exclusiva do Contratante, dependendo de requerimento a ser formalizado pelo Contratado.</p>";
echo "<p align='justify'><b>FISCALIZAÇÃO DO CONTRATO NA SMC: </b>Servidor "."$fiscalNome"." - RF "."$fiscalRF"." como fiscal do contrato e Sr(a) " ."$suplenteNome"." - RF "."$suplenteRF"." como substitut(o)a.<br> 
<b> De acordo com a Portaria nº 5/2012 de SF, haverá compensação financeira, se houver atraso no pagamento do valor devido, por culpa exclusiva do Contratante, dependendo de requerimento a ser formalizado pelo Contratado.</b> </p>";
echo "<p align='justify'><b>PENALIDADES:</b> ".$penalidade.".</p>";
echo "<p align='justify'><b>RESCISÃO CONTRATUAL: </b> DESPACHO - Dar-se-á caso ocorra quaisquer dos atos cabíveis descritos na legislação vigente.<br>
* Contratação, por inexigibilidade da licitação, com fundamento no artigo 25, Inciso III, da Lei Federal nº. 8.666/93, e alterações posteriores, e artigo 1º da Lei Municipal nº. 13.278/02, nos termos dos artigos 16 e 17 do Decreto Municipal nº. 44.279/03.</p>
<b> ** OBSERVAÇÕES:<br/> 
ESTE EMPENHO SUBSTITUI O CONTRATO, CONFORME ARTIGO 62 DA LEI FEDERAL Nº. 8.666/93.</b><br/>
As idéias e opiniões expressas durante as apresentações artísticas e culturais não representam a posição da Secretaria Municipal de Cultura, sendo os artistas e seus representantes os únicos e exclusivos responsáveis pelo conteúdo de suas manifestações, ficando a Municipalidade de São Paulo com direito de regresso sobre os mesmos, inclusive em caso de indenização por dano material, moral ou à imagem de terceiros.
</p>";
echo "<p align='justify'><b>DESPACHO</b><br>".$amparo."</p>";
echo "</body>";
echo "</html>";
?>
