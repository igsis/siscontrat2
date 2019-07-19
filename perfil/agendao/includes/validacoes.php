<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados('agendoes', 'id', $idEvento);

$erros = [];

if (($evento['produtor_id'] == "") || ($evento['produtor_id'] == null)) {
    array_push($erros,"Produtor não cadastrado!");
}

$ocorrencias = $con->query("SELECT * FROM agendao_ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND tipo_ocorrencia_id = 3 AND publicado = '1'");
$numOcorrencias = $ocorrencias->num_rows;
if ($numOcorrencias == 0) {
    array_push($erros, "Não há ocorrência cadastrada!");
}