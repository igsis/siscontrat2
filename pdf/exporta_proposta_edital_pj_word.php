<?php

//require '../include/';
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();


//CONSULTA
$idUser = $_POST['idUser'];
$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$pessoa = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
$ocorrencias = $con->query("SELECT atracao_id, tipo_ocorrencia_id FROM ocorrencias WHERE tipo_ocorrencia_id != 3 AND publicado = 1 AND origem_ocorrencia_id =  " . $evento['id']);

$cargaHoraria = 0;

while ($linhaOco = mysqli_fetch_array($ocorrencias)) {

    if($linhaOco['tipo_ocorrencia_id'] == 1){
        $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = " . $linhaOco['atracao_id'];
        $carga = $con->query($sqlCarga);

        if($carga->num_rows > 0 || $cargaHoraria != 0){
            while($cargaArray = mysqli_fetch_array($carga)){
                $cargaHoraria =  $cargaHoraria + (int)$cargaArray['carga_horaria'];
            }
        }
    }
}

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];

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

if ($pessoa['ccm'] != "" || $pessoa['ccm'] != NULL) {
    $ccm = $pessoa['ccm'];
} else {
    $ccm = "Não Cadastrado.";
}

if($cargaHoraria == 0){
    $ch = "Não possuí";
}else{
    $ch = $cargaHoraria;
}

$idPenal = $_GET['penal'];
alteraStatusPedidoContratos($idPedido, "proposta", $idPenal, $idUser);

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=proposta_edital_pj_$idPedido.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<body>";

echo
    "<p>(A)</p>" .
    "<p align='center'><strong>PESSOA JURÍDICA</strong></p>" .
    "<p><i>(Empresário exclusivo SE FOR O CASO)</i></p>" .
    "<p><strong>Razão Social:</strong> " . $pessoa['razao_social'] . "</p>" .
    "<p><strong>CNPJ:</strong> " . $pessoa['cnpj'] . "</p>" .
    "<p><strong>CCM:</strong> " . $ccm . "</p>" .
    "<p><strong>Endereço:</strong> " . $endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " . $endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf'] . "</p>" .
    "<p><strong>Telefone(s):</strong> " . $tel . "</p>" .
    "<p><strong>E-mail:</strong> " . $pessoa['email'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Representante:</strong> " . $representante['nome'] . "</p>" .
    "<p><strong>RG:</strong> " . $representante['rg'] . "</p>" .
    "<p><strong>CPF:</strong> " . $representante['cpf'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>(B)</p>" .
    "<p align='center'><strong>PROPOSTA</strong></p>" .
    "<p align='right'>" . $ano . "-" . $pedido['id'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p><strong>Objeto:</strong> " . $objeto . "</p>" .
    "<p><strong>Data / Período:</strong> " . $periodo . " - conforme cronograma</p>" .
    "<p><strong>Carga Horária:</strong> " . $ch . "</p>" .
    "<p><strong>Local(ais):</strong> " . listaLocais($evento['id'], '1') . "</p>" .
    "<p><strong>Valor:</strong> " . dinheiroParaBr($pedido['valor_total']) . " (" . valorPorExtenso($pedido['valor_total']) . ")</p>" .
    "<p><strong>Forma de Pagamento:</strong> " . $pedido['forma_pagamento'] . "</p>" .
    "<p><strong>Justificativa:</strong> " . $pedido['justificativa'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>___________________________</p>" .
    "<p>" . $representante['nome'] . "</p>" .
    "<p>RG: " . $representante['rg'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>&nbsp;</p>" .
    "<p>(C)</p>" .
    "<p align='center'><strong>OBSERVAÇÃO</strong></p>" .
    "<p>As idéias e opiniões expressas durante as apresentações artísticas e culturais não representam a posição da Secretaria Municipal de Cultura, sendo os artistas e seus representantes os únicos e exclusivos responsáveis pelo conteúdo de suas manifestações, ficando a Municipalidade de São Paulo com direito de regresso sobre os mesmos, inclusive em caso de indenização por dano material, moral ou à imagem de terceiros.</p>" .
    "<p>Os registros das atividades e ações poderão ser utilizados para fins institucionais de divulgação, promoção e difusão do Programa e da Secretaria Municipal de Cultura.</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'><strong>DECLARAÇÕES</strong></p>" .
    "<p>Declaramos que não temos débitos perante as Fazendas Públicas, Federal, Estadual e, em especial perante a Prefeitura do Município de São Paulo.</p>" .
    "<p>Declaramos que estamos cientes e de acordo com todas as regras do [INSIRA O TÍTULO DO EDITAL AQUI. Ex: Edital de Concurso Programa de Exposições 2016].</p>" .
    "<p>Declaramos que estamos cientes da aplicação das penalidades previstas [INSIRA A CLÁUSULA DA PENALIDADE AQUI. Ex: na cláusula 10 do Edital de Concurso Programa de Exposições 2016.]</p>" .
    "<p>As penalidades serão aplicadas sem prejuízo das demais sanções previstas na legislação que rege a matéria.</p>" .
    "<p>Declaramos que estamos cientes que do valor do serviço serão descontados os impostos cabíveis." .
    "<p>Todas as informações precedentes são formadas sob as penas da Lei.</p>" .
    "<p>&nbsp;</p>" .
    "<p>Data: ____ / ____ / " . $ano . "</p>" .
    "<p>&nbsp;</p>" .
    "<p>___________________________</p>" .
    "<p>" . $representante['nome'] . "</p>" .
    "<p>RG: " . $representante['rg'] . "</p>" .
    "<p>&nbsp;</p>" .
    "<p align='center'><strong>CRONOGRAMA</strong></p>" .
    "<p>" . $objeto . "</p>" .
    "<p>&nbsp;</p>";

if ($evento['tipo_evento_id'] == 1) {
    $cronograma = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id'] . " AND tipo_ocorrencia_id = 1 AND publicado = 1");
    while ($aux = mysqli_fetch_array($cronograma)) {
        $checaTipo = $con->query("SELECT acao_id FROM acao_atracao WHERE atracao_id = " . $aux['atracao_id'] )->fetch_array();
        $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = " . $checaTipo['acao_id'] . " AND publicado = 1")->fetch_array();
        $acao = $tipoAcao['acao'];

        $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
        $hour = exibirHora($aux['horario_inicio']) . "h - " . exibirHora($aux['horario_fim']) . "h";
        $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'] . " AND publicado = 1")->fetch_array();
        $lugar = $local['local'];

        echo "<p><strong>Ação:</strong> " . $acao . "</p>";
        echo "<p><strong>Data/Período:</strong> " . $dia . "</p>";
        echo "<p><strong>Horário:</strong> " . $hour . "</p>";
        echo "<p><strong>Local:</strong> " . $lugar . "</p>";
        echo "<p>&nbsp;</p>";
    }

} elseif ($evento['tipo_evento_id'] == 2) {
    $filmes = $con->query("SELECT id, filme_id FROM filme_eventos WHERE evento_id = " . $evento['id']);
    foreach ($filmes as $filme){
        $dadosFilme = $con->query("SELECT duracao, titulo FROM filmes WHERE id = " . $filme['filme_id'] . " AND publicado = 1")->fetch_array();
        $cronograma = $con->query("SELECT * FROM ocorrencias WHERE publicado = 1 AND tipo_ocorrencia_id = 2 AND origem_ocorrencia_id = " . $evento['id'] . " AND atracao_id = " .$filme['id']);
        while ($aux = mysqli_fetch_array($cronograma)) {

            $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = 1")->fetch_array();
            $acao = $tipoAcao['acao'];

            echo "<p><strong> Título: </strong>" . $dadosFilme['titulo'] . "</p>" .
                "<p><strong>Duração: </strong>" . $dadosFilme['duracao'] . " Minuto(s)" . "</p>";

            $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
            $hour = exibirHora($aux['horario_inicio']) . "h - " . exibirHora($aux['horario_fim']) . "h";
            $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'] . " AND publicado = 1")->fetch_array();
            $lugar = $local['local'];

            echo "<p><strong>Ação:</strong> " . $acao . "</p>";
            echo "<p><strong>Data/Período:</strong> " . $dia . "</p>";
            echo "<p><strong>Horário:</strong> " . $hour . "</p>";
            echo "<p><strong>Local:</strong> " . $lugar . "</p>";
            echo "<p>&nbsp;</p>";

        }
    }
}

echo
    "<p>&nbsp;</p>" .
    "<p>___________________________</p>" .
    "<p>" . $representante['nome'] . "</p>" .
    "<p>RG: " . $representante['rg'] . "</p>" .
    "<p>&nbsp;</p>";

echo "</body>";
echo "</html>";
?>

