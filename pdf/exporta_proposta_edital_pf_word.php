<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


//CONSULTA
$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$sqlLocal = "SELECT l.local FROM locais l INNER JOIN ocorrencias o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = " . $evento['id'] ." AND o.publicado = 1";
$queryLocal = mysqli_query($con, $sqlLocal);
$local = '';
while ($locais = mysqli_fetch_array($queryLocal)) {
    $local = $local . '; ' . $locais['local'];
}
$local = substr($local, 1);
$idEvento = $ocorrencia['origem_ocorrencia_id'];

$idNacionalidade = $pessoa['nacionalidade_id'];
$sqlNacionalidade = "SELECT nacionalidade FROM nacionalidades WHERE id = '$idNacionalidade'";
$nacionalidade = $con->query($sqlNacionalidade)->fetch_array();


$idPessoa = $pessoa['id'];
$sqlDRT = "SELECT drt FROM drts WHERE pessoa_fisica_id = $idPessoa";
$drt = $con->query($sqlDRT)->fetch_array();

$sqlNIT = "SELECT nit FROM nits WHERE pessoa_fisica_id = $idPessoa";
$nit = $con->query($sqlNIT)->fetch_array();

$sqlEndereco = "SELECT logradouro, numero, complemento, bairro, cidade, uf FROM pf_enderecos WHERE pessoa_fisica_id = '$idPessoa'";
$endereco = $con->query($sqlEndereco)->fetch_array();

$periodo = retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias');

$ano = date('Y');

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPessoa'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$idAtracao = $ocorrencia['atracao_id'];
$sqlCheca = $con->query("SELECT * FROM acao_atracao WHERE atracao_id = '$idAtracao' AND acao_id = 8");
$checa = mysqli_num_rows($sqlCheca);

if ($checa != 0) {
    $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = '$idAtracao'";
    $carga = $con->query($sqlCarga)->fetch_array();
    $carga = $carga['carga_horaria'];
} else if ($checa == 0) {
    $carga = "Não se aplica.";
}

if ($drt['drt'] != "" || $drt['drt'] != NULL) {
    $drt = $drt['drt'];
} else {
    $drt = "Não Cadastrado.";
}

if ($pessoa['ccm'] != "" || $pessoa['ccm'] != NULL) {
    $ccm = $pessoa['ccm'];
} else {
    $ccm = "Não Cadastrado.";
}

if($pessoa['passaporte'] != NULL){
    $rg_cpf_passaporte = "<p><strong>Passaporte:</strong> " . $pessoa['passaporte'] . "</p>";
    $label = "<p>Passaporte: " . $pessoa['passaporte'] . "</p>";
}else{
    $rg_cpf_passaporte = "<p><strong>RG:</strong> " . $pessoa['rg'] . "</p>
                          <p><strong>CPF:</strong> " . $pessoa['cpf'] . "<p>";
    $label = "<p>RG: " . $pessoa['rg'] . "</p>";
}

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=proposta_edital_pf_$idPedido.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<body>";

echo
    "<p>(A)</p>" .
    "<p align='center'><strong>CONTRATADO</strong></p>" .
    "<p><i>(Quando se tratar de grupo, o líder do grupo)</i></p>" .
    "<p><strong>Nome:</strong> " . $pessoa['nome'] . "</p>" .
    "<p><strong>Nome Artístico:</strong> " . $pessoa['nome_artistico'] == NULL ? "Não cadastrado" : $pessoa['nome_artistico'] . "</p>" .
    "<p><strong>Nacionalidade:</strong> " . $nacionalidade['nacionalidade'] . "</p>" .
    $rg_cpf_passaporte .
    "<p><strong>CCM:</strong> " . $ccm . "</p>" .
    "<p><strong>DRT:</strong> " . $drt . "</p>" .
    "<p><strong>Endereço:</strong> " . $endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " . $endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf'] . "</p>" .
    "<p><strong>Telefone:</strong> " . $tel . "</p>" .
    "<p><strong>E-mail:</strong> " . $pessoa['email'] . "</p>" .
    "<p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> " . $nit['nit'] == NULL ? "Não cadastrado" : $nit['nit'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>(B)</p>" .
    "<p align='center'><strong>PROPOSTA</strong></p>" .
    "<p align='right'>" . $ano . "-" . $pedido['id'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Objeto:</strong> " . $objeto . "</p>" .
    "<p><strong>Data / Período:</strong> " . $periodo . " - conforme cronograma</p>" .
    "<p><strong>Carga Horária:</strong> " . $carga . "</p>" .
    "<p><strong>Local:</strong> " . $local . "</p>" .
    "<p><strong>Valor:</strong> " . dinheiroParaBr($pedido['valor_total']) . " (" . valorPorExtenso($pedido['valor_total']) . ")</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . $pedido['forma_pagamento'] . "</p>" .
    "<p><strong>Justificativa:</strong> " . $pedido['justificativa'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>___________________________</p>" .
    "<p>" . $pessoa['nome'] . "</p>" .
    $label .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>(C)</p>" .
    "<p align='center'><strong>OBSERVAÇÃO</strong></p>" .
    "<p>As idéias e opiniões expressas durante as apresentações artísticas e culturais não representam a posição da Secretaria Municipal de Cultura, sendo os artistas e seus representantes os únicos e exclusivos responsáveis pelo conteúdo de suas manifestações, ficando a Municipalidade de São Paulo com direito de regresso sobre os mesmos, inclusive em caso de indenização por dano material, moral ou à imagem de terceiros.</p>" .
    "<p>Os registros das atividades e ações poderão ser utilizados para fins institucionais de divulgação, promoção e difusão do Programa e da Secretaria Municipal de Cultura.</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'><strong>DECLARAÇÕES</strong></p>" .
    "<p>Declaro que não tenho débitos perante as fazendas públicas, federal, estadual e, em especial perante a Prefeitura do Município de São Paulo.</p>" .
    "<p>Declaro que estou ciente e de acordo com todas as regras do [INSIRA O TÍTULO DO EDITAL AQUI. Ex: Edital de Concurso Programa de Exposições 2016].</p>" .
    "<p>Declaro que estou ciente da aplicação das penalidades previstas na cláusula [INSIRA A CLÁUSULA DA PENALIDADE AQUI. Ex: na cláusula 10 do Edital de Concurso Programa de Exposições 2016.].As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria.</p>" .
    "<p>Declaro, ainda, estar ciente que do valor do serviço serão descontados os impostos cabíveis.</p>" .
    "<p>Declaro, sob as penas da Lei, que não sou servidor público municipal e que não há, de minha parte, impedimento para contratar com a [INSIRA A UNIDADE AQUI. Ex: Prefeitura do Município de São Paulo/Secretaria Municipal de Cultura/Centro Cultural São Paulo], mediante o pagamento de cachê.</p>" .
    "<p>Todas as informações precedentes são formadas sob as penas da Lei.</p>" .
    "<p>&nbsp;</p>" .
    "<p>Data: ____ / ____ / " . $ano . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>___________________________</p>" .
    "<p>" . $pessoa['nome'] . "</p>" .
    $label .
    "<p>&nbsp;</p>" .
    "<p align='center'><strong>CRONOGRAMA</strong></p>" .
    "<p>" . $objeto . "</p>" .
    "<p>&nbsp;</p>";

$cronograma = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id']);
while ($aux = mysqli_fetch_array($cronograma)) {
    if ($aux['tipo_ocorrencia_id'] == 2) {
        $testaFilme = $con->query("SELECT filme_id FROM filme_eventos WHERE evento_id = $idEvento")->fetch_array();
        $filme = $con->query("SELECT duracao, titulo FROM filmes WHERE id = " . $testaFilme['filme_id'])->fetch_array();
        $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = 1")->fetch_array();
        $acao = $tipoAcao['acao'];
        $labelFilme = "<p><strong> Título: </strong>" . $filme['titulo'] . "</p>" .
            "<p><strong>Duração: </strong>" . $filme['duracao'] . " Hora(s)" . "</p>";
    } else {
        $checaTipo = $con->query("SELECT acao_id FROM acao_atracao WHERE atracao_id = $idAtracao ")->fetch_array();
        $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = " . $checaTipo['acao_id'])->fetch_array();
        $acao = $tipoAcao['acao'];
        $labelFilme = "";
    }
    $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
    $hour = $aux['horario_inicio'] . " - " . $aux['horario_fim'];
    $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'])->fetch_array();
    $lugar = $local['local'];

    echo $labelFilme;
    echo "<p><strong>Ação:</strong> " . $acao . "</p>";
    echo "<p><strong>Data/Período:</strong> " . $dia . "</p>";
    echo "<p><strong>Horário:</strong> " . $hour . "</p>";
    echo "<p><strong>Local:</strong> " . $lugar . "</p>";
    echo "<p>&nbsp;</p>";
}

echo
    "<p>&nbsp;</p>" .
    "<p>___________________________</p>" .
    "<p>" . $pessoa['nome'] . "</p>" .
    $label .
    "<p>&nbsp;</p>";

echo "</body>";
echo "</html>";
?>
