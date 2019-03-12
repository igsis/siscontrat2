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

if ($nRows > 0) {

    echo "testeee";

/*    for ($i = 1; $i <= $parcelas; $i++) {
        $parcela = $parcelas[$i];
        $valor = dinheiroParaBr($arrayValor[$i]);
        $dataPagamento =  exibirDataMysql($arrayKit[$i]);

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$parcela'";



        if (mysqli_query($con, $sqlUpdate)) {
            gravarLog($sqlUpdate);
        }
    }*/

} else {

    for ($i = 1; $i <= $parcelas; $i++) {
        $valor = dinheiroDeBr($arrayValor[$i]);
        $sql = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento, publicado) VALUES ('$idPedido', '$i', '$valor', ' $arrayKit[$i]', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
            echo $sql;

            $parcela = recuperaUltimo("parcelas");

            if (isset($arrayInicial) && isset($arrayFinal)) {

                $sqlComplemento = "INSERT INTO parcela_complementos (parcela_id, data_inicio, data_fim, carga_horaria, publicado) VALUES ('$parcela', '$arrayInicial[$i]', '$arrayFinal[$i]', '$horas[$i]', 1)";

                if (mysqli_query($con, $sqlComplemento)) {
                    gravarLog($sqlComplemento);
                }
            }
        }
    }
}




