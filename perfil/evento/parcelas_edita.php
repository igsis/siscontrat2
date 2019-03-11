<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['arrayValor'] ?? NULL;
$arrayKit = $_POST['arrayKit'] ?? NULL;

for ($i = 1; $i <= $parcelas; $i++) {
    $parcela = $parcelas[$i];
    $valor = dinheiroParaBr($arrayValor[$i]);
    $dataPagamento =  exibirDataMysql($arrayKit[$i]);

    $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$parcela'";

    echo $sqlUpdate;

    if (mysqli_query($con, $sqlUpdate)) {
        gravarLog($sqlUpdate);
    }
}




