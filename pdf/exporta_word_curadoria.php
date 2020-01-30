<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$sql = "SELECT e.id, a.id as idAtracao, e.nome_evento, e.sinopse,
a.ficha_tecnica, c.classificacao_indicativa
FROM eventos AS e
INNER JOIN atracoes AS a ON e.id = a.evento_id
INNER JOIN classificacao_indicativas AS c ON c.id = a.classificacao_indicativa_id
WHERE e.publicado = 1 AND a.publicado = 1";

$query = mysqli_query($con, $sql);
//contador de eventos e de ocorrencias
$e = 0;
$o = 0;
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=rlt_curadoria.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<body>";
while($evento = mysqli_fetch_array($query)){
    $e = $e + 1;
echo "<p align='center'><b>Evento #</b>" . $e . "</p>";
echo "<p align='justify'><b>Nome do Evento: </b>".$evento['nome_evento']."</p>";
echo "<p align='justify'><b>Sinopse: </b>" . $evento['sinopse'] . "</p>";
echo "<p align='justify'><b>Ficha Técnica: </b>". $evento['ficha_tecnica'] ."</p>";
echo "<p align='justify'><b>Classificação Indicativa: </b>". $evento['classificacao_indicativa'] ."</p>";
echo "<p>&nbsp;</p>";

$cronograma = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id'] . " AND publicado = 1");

while ($aux = mysqli_fetch_array($cronograma)) {
    $o = $o + 1;
    echo "<p align='center'><b>Ocorrencia #". $o ." do Evento: ". $evento['nome_evento'] ."</b></p>";
    if ($aux['tipo_ocorrencia_id'] == 2) {
        $testaFilme = $con->query("SELECT filme_id FROM filme_eventos WHERE evento_id =" . $evento['id'])->fetch_array();
        $filme = $con->query("SELECT duracao, titulo FROM filmes WHERE id = " . $testaFilme['filme_id'] . " AND publicado = 1")->fetch_array();
        $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = 1")->fetch_array();
        $acao = $tipoAcao['acao'];

        echo "<p align='justify'><b>Título: </b>". $filme['titulo'] .".</p>";       
        echo "<p align='justify'><b>Duração: </b>". $filme['duracao'] . " Minuto(s).</p>";
        
    } else {
        $checaTipo = $con->query("SELECT acao_id FROM acao_atracao WHERE atracao_id = " . $evento['idAtracao'])->fetch_array();
        $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = " . $checaTipo['acao_id'] . " AND publicado = 1")->fetch_array();
        $acao = $tipoAcao['acao'];
    }
    $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
    $hour = $aux['horario_inicio'] . " - " . $aux['horario_fim'];
    $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'] . " AND publicado = 1")->fetch_array();
    $lugar = $local['local'];

    echo "<p align='justify'><b>Ação: </b>". $acao . "</p>";
    echo "<p align='justify'><b>Data/Perído: </b>". $dia . "</p>";
    echo "<p align='justify'><b>Horário: </b>". $hour . "</p>";
    echo "<p align='justify'><b>Local: </b>". $lugar . "</p>";

    echo "<p>&nbsp;</p>";
    echo "<p>&nbsp;</p>";
}
$o = 0;

}
echo "</body>";
echo "</html>";
?>

