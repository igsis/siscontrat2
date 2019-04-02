<?php
$con = bancoMysqli();
$conn = bancoPDO();

$idPedido = $_SESSION['idPedido'];

$sql = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido'";
$nums = $conn->query($sql)->rowCount();

$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['valores'] ?? [];
$arrayKit = $_POST['datas'] ?? [];

if ($nums < $parcelas){
    $faltando = $nums - $parcelas;

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
            echo "Erro ao editar!";
        }
    }

    for ($i = $nums + 1; $i <= $parcelas; $i++) {
        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento = $arrayKit[$i];

        $sqlInsert = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento, publicado) VALUES ('$idPedido', '$i', '$valor', ' $arrayKit[$i]', 1)";

        if (mysqli_query($con, $sqlInsert)) {
            $sqlPedido = "UPDATE pedidos SET numero_parcelas = '$parcelas'";
            if (mysqli_query($con, $sqlPedido)) {
                gravarLog($sqlInsert);
                gravarLog($sqlPedido);
            } else {
                echo "Erro ao editar!";
            }
        }
    }
} elseif ($nums > $parcelas) {

    $sobrando = $parcelas - $nums;

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
            echo "Erro ao editar!";
        }
    }

    for ($i = $parcelas + 1; $i <= $nums; $i++) {
        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento = $arrayKit[$i];

        $sqlDelete = "DELETE FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

        if (mysqli_query($con, $sqlDelete)) {
            $sqlPedido = "UPDATE pedidos SET numero_parcelas = '$parcelas'";
            if (mysqli_query($con, $sqlPedido)) {
                gravarLog($sqlDelete);
                gravarLog($sqlPedido);
            } else {
                echo "Erro ao editar!";
            }
        }
    }

} else {

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
            echo "Erro ao editar!";
        }
    }
}




