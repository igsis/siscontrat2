<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['valores'] ?? [];
$arrayKit = $_POST['datas'] ?? [];

for ($i = 1; $i <= $parcelas; $i++) {
    if(!isset($arrayValor[$i], $arrayKit[$i])){
        continue;
    }

    $valor = dinheiroDeBr($arrayValor[$i]);
    $dataPagamento =  $arrayKit[$i];

    $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

    if (mysqli_query($con, $sqlUpdate)) {
        gravarLog($sqlUpdate);
    }else {
        "Erro ao editar!";
    }
}




