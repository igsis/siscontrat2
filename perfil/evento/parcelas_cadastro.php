<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['arrayValor'] ?? NULL;
$arrayKit = $_POST['arrayKit'] ?? NULL;

if (isset($arrayValor) && isset($arrayKit)) {

    for ($i = 1; $i <= $parcelas; $i++) {
        $valor = dinheiroDeBr($arrayValor[$i]);
        $pagamento = $arrayKit[$i];

        $sql = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento, publicado) VALUES ('$idPedido', '$i', '$valor', '$pagamento', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);

        }
    }
}



