<?php
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

//CONSULTA
$idPedido = $_POST['idPedido'];

$pedido = $con->query("SELECT p.numero_processo, pj.razao_social, pj.cnpj, pe.logradouro, pe.numero, pe.complemento, pe.bairro, pe.cidade, pe.uf, pe.cep 
    FROM pedidos p 
        INNER JOIN pessoa_juridicas pj on p.pessoa_juridica_id = pj.id 
        INNER JOIN pj_enderecos pe on pj.id = pe.pessoa_juridica_id
    WHERE p.publicado = 1 AND p.id = '$idPedido'")->fetch_assoc();

$dataAtual = dataHoraNow();

// GERANDO O WORD:
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$dataAtual - Processo SEI ".$pedido['numero_processo']." - DeclaracaoSemFinsLucrativos.doc");
?>

<html lang="pt-br">
<meta http-equiv="Content-Language" content="pt-br">
<!-- HTML 4 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- HTML5 -->
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
<body>
<p align="center"><strong>Anexo III - Instrução Normativa 1.234/2012</strong></p>
<br>
<p align="center">DECLARAÇÃO INSTITUIÇÕES DE CARÁTER FILANTRÓPICO, RECREATIVO, CULTURAL, CIENTÍFICO E ÀS ASSOCIAÇÕES CIVIS</p>
<br>
<p align="justify">À Secretaria Municipal de Cultura</p>
<br>
<p align="justify"><?= $pedido['razao_social'] ?>, com sede em <?= $pedido['logradouro'].", ".$pedido['numero']." ".$pedido['complemento']." ".$pedido['bairro']. " - ".$pedido['cidade']." - ".$pedido['uf']. " CEP:".$pedido['cep'] ?>, inscrita no CNPJ sob o nº <?=$pedido['cnpj']?> DECLARA à Secretaria Municipal de Cultura, para fins de não incidência na fonte do IR, da CSLL, da Cofins, e da Contribuição para o PIS/Pasep, a que se refere o art. 64 da Lei nº 9.430, de 27 de dezembro de 1996, que é entidade sem fins lucrativos de caráter ............................................., a que se refere o art 15 da Lei nº 9.532, de 10 de dezembro de 1997.</p>
<br>
<p align="justify">Para esse efeito, a declarante informa que:</p>
<br>
<p align="justify">I - preenche os seguintes requisitos, cumulativamente:</p>
<p align="justify">a) é entidade sem fins lucrativos;</p>
<p align="justify">b) presta serviços para os quais foi instituída e os coloca à disposição do grupo de pessoas a que se destinam;</p>
<p align="justify">c) não remunera, por qualquer forma, seus dirigentes por serviços prestados;</p>
<p align="justify">d) aplica integralmente seus recursos na manutenção e desenvolvimento de seus objetivos sociais;</p>
<p align="justify">f) conserva em boa ordem, pelo prazo de 5 (cinco) anos, contado da data da emissão, os documentos que comprovam a origem de suas receitas e a efetivação de suas despesas, bem como a realização de quaisquer outros atos ou operações que venham a modificar sua situação patrimonial; e,</p>
<p align="justify">g) apresenta anualmente Declaração de Informações Econômico-Fiscais da Pessoa Jurídica (DIPJ), em conformidade com o disposto em ato da Secretaria da Receita Federal do Brasil (RFB);</p>
<br>
<p align="justify">II - o signatário é representante legal desta entidade, assumindo o compromisso de informar à RFB e à unidade pagadora, imediatamente, eventual desenquadramento da presente situação e está ciente de que a falsidade na prestação dessas informações, sem prejuízo do disposto no art. 32 da Lei nº 9.430, de 1996, o sujeitará, com as demais pessoas que para ela concorrem, às penalidades previstas na legislação criminal e tributária, relativas à falsidade ideológica (art. 299 do Decreto-Lei nº 2.848, de 7 de dezembro de 1940 - Código Penal) e ao crime contra a ordem tributária (art. 1º da Lei nº 8.137, de 27 de dezembro de 1990).</p>
<br>
<p align="justify">Local e data.....................................................</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="justify">Assinatura do Responsável</p>
</body>
</html>