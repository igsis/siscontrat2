<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();
session_start();


//CONSULTA
$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$pessoa = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$idLocal = $ocorrencia['local_id'];
$sqlLocal = "SELECT local FROM locais WHERE id = '$idLocal'";
$idEvento = $ocorrencia['origem_ocorrencia_id'];
$locais = $con->query($sqlLocal)->fetch_array();

$idPessoa = $pessoa['id'];

$sqlEndereco = "SELECT logradouro, numero, complemento, bairro, cidade, uf FROM pj_enderecos WHERE pessoa_juridica_id = '$idPessoa'";
$endereco = $con->query($sqlEndereco)->fetch_array();

$periodo = retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias');

$sqlPenalidade = "SELECT texto FROM penalidades WHERE id = 20";
$penalidades = $con->query($sqlPenalidade)->fetch_array();

$ano = date('Y');

$sqlTelefone = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPessoa'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$idRepresentante = $pessoa['representante_legal1_id'];
$representante = recuperaDados('representante_legais', 'id', $idRepresentante);

$idAtracao = $ocorrencia['atracao_id'];
$sqlCheca = "SELECT oficina FROM atracoes WHERE id = '$idAtracao'";
$checa = $con->query($sqlCheca)->fetch_array();

if ($checa['oficina'] == 1) {
    $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = '$idAtracao'";
    $carga = $con->query($sqlCarga)->fetch_array();
    $carga = $carga['carga_horaria'];
} else if ($checa['oficina'] == 0) {
    $carga = "Não se aplica.";
}


header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$idPedido.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<body>";

echo
    "<p>(A)</p>".
    "<p align='center'><strong>PESSOA JURÍDICA</strong></p>".
    "<p><i>(Empresário exclusivo SE FOR O CASO)</i></p>".
    "<p><strong>Razão Social:</strong> ".$pessoa['razao_social']."</p>".
    "<p><strong>CNPJ:</strong> ".$pessoa['cnpj']."</p>".
    "<p><strong>CCM:</strong> ".$pessoa['ccm']."</p>".
    "<p><strong>Endereço:</strong> ".$endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " .$endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf'] . "</p>".
    "<p><strong>Telefone:</strong> ".$tel."</p>".
    "<p><strong>E-mail:</strong> ". $pessoa['email'] ."</p>".
    "<p>&nbsp;</p>".
    "<p><strong>Representante:</strong> ".$representante['nome']."</p>".
    "<p><strong>RG:</strong> ".$representante['rg']."</p>".
    "<p><strong>CPF:</strong> ".$representante['cpf']."</p>".
    "<p>&nbsp;</p>".
    "<p>(B)</p>".
    "<p align='center'><strong>PROPOSTA</strong></p>".
    "<p align='right'>".$ano."-".$pedido['id']."</p>".
    "<p>&nbsp;</p>".
    "<p><strong>Objeto:</strong> ".$objeto."</p>".
    "<p><strong>Data / Período:</strong> ".$periodo." - conforme cronograma</p>".
    "<p><strong>Carga Horária:</strong> ".$carga."</p>".
    "<p><strong>Local:</strong> ".$locais['local']."</p>".
    "<p><strong>Valor:</strong> ".dinheiroParaBr($pedido['valor_total']) . " (".valorPorExtenso($pedido['valor_total']).")</p>".
    "<p><strong>Forma de Pagamento:</strong> ".$pedido['forma_pagamento']."</p>".
    "<p><strong>Justificativa:</strong> ".$pedido['justificativa']."</p>".
    "<p>&nbsp;</p>".
    "<p>&nbsp;</p>".
    "<p>___________________________</p>".
    "<p>".$representante['nome']."</p>".
    "<p>RG: ".$representante['rg']."</p>".
    "<p>&nbsp;</p>".
    "<p>&nbsp;</p>".
    "<p>(C)</p>".
    "<p align='center'><strong>OBSERVAÇÃO</strong></p>".
    "<p>As idéias e opiniões expressas durante as apresentações artísticas e culturais não representam a posição da Secretaria Municipal de Cultura, sendo os artistas e seus representantes os únicos e exclusivos responsáveis pelo conteúdo de suas manifestações, ficando a Municipalidade de São Paulo com direito de regresso sobre os mesmos, inclusive em caso de indenização por dano material, moral ou à imagem de terceiros.</p>".
    "<p>Os registros das atividades e ações poderão ser utilizados para fins institucionais de divulgação, promoção e difusão do Programa e da Secretaria Municipal de Cultura.</p>".
    "<p>&nbsp;</p>".
    "<p align='center'><strong>DECLARAÇÕES</strong></p>".
    "<p>Declaramos que não temos débitos perante as Fazendas Públicas, Federal, Estadual e, em especial perante a Prefeitura do Município de São Paulo.</p>".
    "<p>Declaramos que estamos cientes e de acordo com todas as regras do [INSIRA O TÍTULO DO EDITAL AQUI. Ex: Edital de Concurso Programa de Exposições 2016].</p>".
    "<p>Declaramos que estamos cientes da aplicação das penalidades previstas [INSIRA A CLÁUSULA DA PENALIDADE AQUI. Ex: na cláusula 10 do Edital de Concurso Programa de Exposições 2016.]</p>".
    "<p>As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria.</p>".
    "<p>Declaramos que estamos cientes que do valor do serviço serão descontados os impostos cabíveis.".
    "<p>Todas as informações precedentes são formadas sob as penas da Lei.</p>".
    "<p>&nbsp;</p>".
    "<p>Data: ____ / ____ / ".$ano."</p>".
    "<p>&nbsp;</p>".
    "<p>___________________________</p>".
    "<p>".$representante['nome']."</p>".
    "<p>RG: ".$representante['rg']."</p>".
    "<p>&nbsp;</p>".
    "<p align='center'><strong>CRONOGRAMA</strong></p>".
    "<p>".$objeto."</p>".
    "<p>&nbsp;</p>";

echo "<p><strong>Tipo:</strong> ".retornaTipo($evento['id'])."</p>";
echo "<p><strong>Data/Período:</strong> ".$periodo."</p>";
echo "<p><strong>Horário:</strong> ".$ocorrencia['horario_inicio']. ' - ' . $ocorrencia['horario_fim']. "</p>";
echo "<p><strong>Local:</strong> ".$locais['local']."</p>";
echo "<p>&nbsp;</p>";

echo
    "<p>&nbsp;</p>".
    "<p>___________________________</p>".
    "<p>".$representante['nome']."</p>".
    "<p>RG: ".$representante['rg']."</p>".
    "<p>&nbsp;</p>";

echo "</body>";
echo "</html>";
?>

