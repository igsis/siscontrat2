<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

$sql = "SELECT id, tipo_evento_id, nome_evento, sinopse FROM eventos WHERE publicado = 1 AND tipo_evento_id != 3";

$query = mysqli_query($con, $sql);
//contador de eventos e de ocorrencias
$e = 0;
$o = 0;
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=rlt_curadoria.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "<body>";
while ($evento = mysqli_fetch_array($query)) {
    $e = $e + 1;
    echo "<p align='center'><b>Evento #</b>" . $e . "</p>";
    echo "<p align='justify'><b>Nome do Evento: </b>" . $evento['nome_evento'] . "</p>";
    echo "<p align='justify'><b>Sinopse: </b>" . $evento['sinopse'] . "</p>";
    if ($evento['tipo_evento_id'] == 1) {
        $atracoes = $con->query("SELECT a.id, a.ficha_tecnica, ci.classificacao_indicativa FROM atracoes a INNER JOIN classificacao_indicativas ci on a.classificacao_indicativa_id = ci.id WHERE publicado = 1 AND evento_id = " . $evento['id']);
        foreach ($atracoes as $atracao) {
            echo "<p align='justify'><b>Ficha Técnica: </b>" . $atracao['ficha_tecnica'] . "</p>";
            echo "<p align='justify'><b>Classificação Indicativa: </b>" . $atracao['classificacao_indicativa'] . "</p>";
            echo "<p>&nbsp;</p>";

            $cronograma = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id'] . " AND tipo_ocorrencia_id = 1 AND publicado = 1");
            $o = $o + 1;
            while ($aux = mysqli_fetch_array($cronograma)) {
                echo "<p align='center'><b>Ocorrencia #" . $o . " do Evento: " . $evento['nome_evento'] . "</b></p>";
                $checaTipo = $con->query("SELECT acao_id FROM acao_atracao WHERE atracao_id = " . $atracao['id'])->fetch_array();
                $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = " . $checaTipo['acao_id'] . " AND publicado = 1")->fetch_array();
                $acao = $tipoAcao['acao'];

                $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
                $hour = exibirHora($aux['horario_inicio']) . "h - " . exibirHora($aux['horario_fim']) . "h";
                $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'] . " AND publicado = 1")->fetch_array();
                $lugar = $local['local'];

                echo "<p align='justify'><b>Ação: </b>" . $acao . "</p>";
                echo "<p align='justify'><b>Data/Perído: </b>" . $dia . "</p>";
                echo "<p align='justify'><b>Horário: </b>" . $hour . "</p>";
                echo "<p align='justify'><b>Local: </b>" . $lugar . "</p>";

                echo "<p>&nbsp;</p>";
                echo "<p>&nbsp;</p>";

            }
            $o = 0;
        }

    } elseif ($evento['tipo_evento_id'] == 2) {
        $filmes = $con->query("SELECT id, filme_id FROM filme_eventos WHERE evento_id = " . $evento['id']);
        foreach ($filmes as $filme) {
            $dadosFilme = $con->query("SELECT duracao, titulo FROM filmes WHERE id = " . $filme['filme_id'] . " AND publicado = 1")->fetch_array();
            $cronograma = $con->query("SELECT * FROM ocorrencias WHERE publicado = 1 AND tipo_ocorrencia_id = 2 AND origem_ocorrencia_id = " . $evento['id'] . " AND atracao_id = " . $filme['id']);
            $o = $o + 1;
            while ($aux = mysqli_fetch_array($cronograma)) {

                $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = 1")->fetch_array();
                $acao = $tipoAcao['acao'];

                echo "<p align='justify'><b>Título: </b>" . $dadosFilme['titulo'] . ".</p>";
                echo "<p align='justify'><b>Duração: </b>" . $dadosFilme['duracao'] . " Minuto(s).</p>";

                $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
                $hour = exibirHora($aux['horario_inicio']) . "h - " . exibirHora($aux['horario_fim']) . "h";
                $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'] . " AND publicado = 1")->fetch_array();
                $lugar = $local['local'];

                echo "<p align='justify'><b>Ação: </b>" . $acao . "</p>";
                echo "<p align='justify'><b>Data/Perído: </b>" . $dia . "</p>";
                echo "<p align='justify'><b>Horário: </b>" . $hour . "</p>";
                echo "<p align='justify'><b>Local: </b>" . $lugar . "</p>";

                echo "<p>&nbsp;</p>";
                echo "<p>&nbsp;</p>";
            }
            $o = 0;
        }
    }
}
echo "</body>";
echo "</html>";
?>

