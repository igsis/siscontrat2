<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
require_once ("funcoesConecta.php");

$con = bancoMysqli();

if(isset($_GET['idEvento'])){
    $id = $_GET['idEvento'];

    //Locais
    $sqlLocal = "SELECT l.local FROM locais AS l INNER JOIN ocorrencias AS o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = $id AND o.publicado = 1";
    $local = "";
    $queryLocal = mysqli_query($con, $sqlLocal);

    while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
        $local = $local . $linhaLocal['local'] . '<br>';
    }

    $local = substr($local, 0);

    echo $local;
}
