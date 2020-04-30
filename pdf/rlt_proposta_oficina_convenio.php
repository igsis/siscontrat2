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
        }else{
            $cargaHoraria = "Não possuí.";
        }
    }
}
$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];
$sqlLocal = "SELECT l.local FROM locais l INNER JOIN ocorrencias o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = " . $evento['id'] ." AND o.publicado = 1";
$queryLocal = mysqli_query($con, $sqlLocal);
$local = '';
while ($locais = mysqli_fetch_array($queryLocal)) {
    $local = $local . '; ' . $locais['local'];
}
$local = substr($local, 1);

$idNacionalidade = $pessoa['nacionalidade_id'];
$sqlNacionalidade = "SELECT nacionalidade FROM nacionalidades WHERE id = '$idNacionalidade'";
$nacionalidade = $con->query($sqlNacionalidade)->fetch_array();


$idPessoa = $pessoa['id'];
$testaDrt = $con->query("SELECT drt FROM drts WHERE pessoa_fisica_id = $idPessoa");
if ($testaDrt->num_rows > 0) {
    while ($drtArray = mysqli_fetch_array($testaDrt)) {
        $drt = $drtArray['drt'];
    }
} else {
    $drt = "Não Cadastrado.";
}

$testaNit = $con->query("SELECT nit FROM nits WHERE pessoa_fisica_id = $idPessoa");

if ($testaNit->num_rows > 0) {
    while($nitArray = mysqli_fetch_array($testaNit)){
        $nit = $nitArray['nit'];
    }
}else{
    $nit = "Não Cadastrado.";
}

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

if($pessoa['ccm'] != "" || $pessoa['ccm'] != NULL){
    $ccm = $pessoa['ccm'];
}else{
    $ccm = "Não Cadastrado.";
}

if($pessoa['passaporte'] != NULL){
    $rg_cpf_passaporte = "<p><strong>Passaporte:</strong> " . $pessoa['passaporte'] . "</p>";
    $label = "<p>Passaporte: " . $pessoa['passaporte'] . "</p>";
}else{
    $rg_cpf_passaporte = "<p><strong>RG:</strong> " . $pessoa['rg'] . "</p>
                          <p><strong>CPF:</strong> " . $pessoa['cpf'] . "<p>";   
    $label = "<p>RG: " . $pessoa['rg'] . "</p>
              <p>CPF: " . $pessoa['cpf'] . "</p>";
}

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=rlt_proposta_oficina_convenio_$idPedido.doc");
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>

<p>(A)</p>
<p align='center'><strong>CONTRATADO</strong></p>
<p><i>(Quando se tratar de grupo, o líder do grupo)</i></p>
<p><strong>Nome:</strong> <?= $pessoa['nome'] ?></p>
<p><strong>Nome Artístico:</strong> <?= $pessoa['nome_artistico'] == NULL ? "Não cadastrado" : $pessoa['nome_artistico'] ?></p>
<p><strong>Nacionalidade:</strong> <?= $nacionalidade['nacionalidade'] ?></p>
<?=$rg_cpf_passaporte?>
<p><strong>CCM:</strong> <?= $ccm ?></p>
<p><strong>DRT:</strong> <?= $drt ?></p>
<p>
    <strong>Endereço:</strong> <?= $endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " . $endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf'] ?>
</p>
<p><strong>Telefone:</strong> <?= $tel ?></p>
<p><strong>E-mail:</strong> <?= $pessoa['email'] ?></p>
<p><strong>Inscrição no INSS ou nº PIS / PASEP:</strong> <?= $nit ?></p>

<br style='page-break-before: always'>
<p>(B)</p>
<p align='center'><strong>PROPOSTA</strong></p>
<p align='right'><?= $ano ?> - <?= $idPedido ?></p>
<p>&nbsp;</p>
<p><strong>Objeto:</strong> <?= $objeto ?> - <strong>CONVÊNIO FEDERAL N° 849979/2017</strong> cujo o objeto é a
    Contratação artística de oficinas de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas
    de Cultura e Centros Culturais da Secretaria Municipal de Cultura.</p>
<p><strong>Data / Período:</strong> <?= $periodo ?> - conforme cronograma</p>
<p><strong>Carga Horária:</strong> <?= $cargaHoraria ?></p>
<p><strong>Local:</strong> <?= $local ?></p>
<p><strong>Valor:</strong> <?= $pedido['valor_total'] ?> (<?= valorPorExtenso($pedido['valor_total']) ?>)</p>
<p><strong>Forma de Pagamento:</strong> <?= $pedido['forma_pagamento'] ?></p>
<p><strong>Justificativa:</strong> <?= $pedido['justificativa'] ?></p>
<p>&nbsp;</p>
<p>___________________________</p>
<p><?= $pessoa['nome'] ?></p>
<?=$label?>

<br style='page-break-before: always'>
<p>(C)</p>
<p align='center'><strong>EDITAL DE CREDENCIAMENTO Nº 02/2018 – SMC/GAB</strong></p>
<p align="center"><strong>CONVÊNIO FEDERAL N° 849979/2017</strong>, cujo o objeto é a Contratação artística de oficinas
    de dança, teatro, circo, literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da
    Secretaria Municipal de Cultura.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><strong>Declaro que:</strong></p>
<ul>
    <li>Conheço e aceito incondicionalmente as regras do Edital n. 02/2018 – SMC/GAB de Credenciamento;</li>
    <li>Em caso de seleção, responsabilizo-me pelo cumprimento da agenda acordada entre o equipamento municipal e o
        Oficineiro, no tocante ao local, data e horário, para a realização da Oficina. Em acordo com o previsto no
        convênio federal n° 849979/2017
    </li>
    <li>Não sou servidor público municipal.</li>
    <li>Estou ciente de que a contratação não gera vínculo trabalhista entre a Municipalidade e o Contratado.</li>
    <li>Estou ciente da aplicação de penalidades conforme item 11 do Edital de Credenciamento nº 02/2018 SMC/GAB</li>
</ul>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Data: ____ / ____ / <?= $ano ?></p>
<p>&nbsp;</p>
<p>___________________________</p>
<p><?= $pessoa['nome'] ?></p>
<?=$label?>

<br style='page-break-before: always'>
<p align='center'><strong>CRONOGRAMA</strong></p>
<p><?= $objeto ?> - CONVÊNIO FEDERAL N° 849979/2017 - Contratação artística de oficinas de dança, teatro, circo,
    literatura e música para realização em Bibliotecas, Casas de Cultura e Centros Culturais da Secretaria Municipal de
    Cultura</p>
<p>&nbsp;</p>
<?php
if ($evento['tipo_evento_id'] == 1) {
    $cronograma = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id'] . " AND tipo_ocorrencia_id = 1 AND publicado = 1");
    while ($aux = mysqli_fetch_array($cronograma)) {
        $checaTipo = $con->query("SELECT acao_id FROM acao_atracao WHERE atracao_id = " . $aux['atracao_id'])->fetch_array();
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
}?>

<p>&nbsp;</p>
<p>___________________________</p>
<p><?= $pessoa['nome'] ?></p>
<?=$label?>
<p>&nbsp;</p>

</body>
</html>
