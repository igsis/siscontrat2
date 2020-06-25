<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
require_once("funcoesConecta.php");

$con = bancoMysqli();

if (isset($_GET['idEvento'])) {
    $id = $_GET['idEvento'];
    $content = $_GET['conteudo'];

    if ($content == "local") {

        //Locais
        $sqlLocal = "SELECT l.local FROM locais AS l INNER JOIN ocorrencias AS o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = $id AND o.publicado = 1";
        $local = "";
        $queryLocal = mysqli_query($con, $sqlLocal);

        while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
            $local = $local . $linhaLocal['local'] . '<br>';
        }

        $local = substr($local, 0);

        echo $local;

    } else if ($content == "inst") {

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

}
