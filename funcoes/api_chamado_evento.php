<?php

require_once 'funcoesConecta.php';
require_once 'funcoesGerais.php';
$con = bancoMysqli();
$conn = bancoPDO();

if(isset($_GET['idEvento'])){
    $id = $_GET['idEvento'];
    $resultado = numeroChamados($id,true);
    echo json_encode($resultado);
}