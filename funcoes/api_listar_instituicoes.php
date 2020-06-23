<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
require_once ("funcoesConecta.php");

$con = bancoMysqli();

if(isset($_GET['idEvento'])){
    $id = $_GET['idEvento'];

    //Instituições
    $sqlInst = "SELECT i.nome FROM instituicoes AS i INNER JOIN ocorrencias AS o ON o.instituicao_id = i.id WHERE o.origem_ocorrencia_id = $id AND o.publicado = 1";
    $inst = "";
    $queryInst = mysqli_query($con, $sqlInst);

    while ($linhaInst = mysqli_fetch_array($queryInst)) {
        $inst = $inst . $linhaInst['nome'] . '<br>';
    }

    $inst = substr($inst, 0);

    echo $inst;
}

