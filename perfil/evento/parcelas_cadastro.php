<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];

$parcelas = $_POST['parcelas'];
$arrayValor = $_POST['arrayValor'];
$arrayKit = $_POST['arrayKit'];

//oficinas
$arrayInicial = $_POST['arrayInicial'] ?? NULL;
$arrayFinal = $_POST['arrayFinal'] ?? NULL;
$horas = $_POST['horas'] ?? NULL;

$sqlVerifica = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido'";
$query = mysqli_query($con, $sqlVerifica);
$nRows = mysqli_num_rows($query);


for ($i = 1; $i <= $parcelas; $i++) {
    $valor = dinheiroDeBr($arrayValor[$i]);
    $sql = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento, publicado) VALUES ('$idPedido', '$i', '$valor', ' $arrayKit[$i]', 1)";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);

        $update = "UPDATE pedidos SET numero_parcelas = '$parcelas', data_kit_pagamento = null";
        mysqli_query($con, $update);
        gravarLog($update);

        $parcela = recuperaUltimo("parcelas");

        if (isset($arrayInicial) && isset($arrayFinal)) {

            $sqlComplemento = "INSERT INTO parcela_complementos (parcela_id, data_inicio, data_fim, carga_horaria, publicado) VALUES ('$parcela', '$arrayInicial[$i]', '$arrayFinal[$i]', '$horas[$i]', 1)";

            if (mysqli_query($con, $sqlComplemento)) {
                gravarLog($sqlComplemento);
            }
        }
    }
}




