<?php
$con = bancoMysqli();
$conn = bancoPDO();

$idPedido = $_SESSION['idPedido'];

$sql = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido'";
$nums = $conn->query($sql)->rowCount();

$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['valores'] ?? [];
$arrayKit = $_POST['datas'] ?? [];

//oficinas
$arrayInicial = $_POST['arrayInicial'] ?? NULL;
$arrayFinal = $_POST['arrayFinal'] ?? NULL;
$horas = $_POST['horas'] ?? NULL;

if ($nums < $parcelas){
    echo "teste 1";
    $faltando = $nums - $parcelas;

    for ($i = 1; $i <= $parcelas; $i++) {
        if(!isset($arrayValor[$i], $arrayKit[$i])){
            continue;
        }

        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento =  $arrayKit[$i];

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

        if (mysqli_query($con, $sqlUpdate)) {

            if (isset($arrayInicial) && isset($arrayFinal)) {

                $sqlVerifica = "SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";
                $parcela = mysqli_query($con, $sqlVerifica);

                $sqlComplemento = "UPDATE parcela_complementos SET parcela_id = '$parcela',  data_inicio = '$arrayInicial[$i]',  data_fim = '$arrayFinal[$i]', carga_horaria = '$horas[$i]'";

                if (mysqli_query($con, $sqlComplemento)) {
                    gravarLog($sqlComplemento);
                }
            }
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

                $parcela = recuperaUltimo("parcelas");

                if (isset($arrayInicial) && isset($arrayFinal)) {

                    $sqlComplemento = "INSERT INTO parcela_complementos (parcela_id, data_inicio, data_fim, carga_horaria, publicado) VALUES ('$parcela', '$arrayInicial[$i]', '$arrayFinal[$i]', '$horas[$i]', 1)";

                    if (mysqli_query($con, $sqlComplemento)) {
                        gravarLog($sqlComplemento);
                    }
                }

                gravarLog($sqlInsert);
                gravarLog($sqlPedido);
            } else {
                echo "Erro ao editar!";
            }
        }
    }
} elseif ($nums > $parcelas) {

    echo "teste 2";

    $sobrando = $parcelas - $nums;

    for ($i = 1; $i <= $parcelas; $i++) {
        if(!isset($arrayValor[$i], $arrayKit[$i])){
            continue;
        }

        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento =  $arrayKit[$i];

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

        if (mysqli_query($con, $sqlUpdate)) {

            if (isset($arrayInicial) && isset($arrayFinal)) {

                $sqlVerifica = "SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";
                $parcela = mysqli_query($con, $sqlVerifica);

                $sqlComplemento = "UPDATE parcela_complementos SET parcela_id = '$parcela',  data_inicio = '$arrayInicial[$i]',  data_fim = '$arrayFinal[$i]', carga_horaria = '$horas[$i]'";

                if (mysqli_query($con, $sqlComplemento)) {
                    gravarLog($sqlComplemento);
                }
            }
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

            if (isset($arrayInicial) && isset($arrayFinal)) {

                $sqlComplemento = "DELETE FROM parcela_complementos WHERE parcela_id = '$i'";

                if (mysqli_query($con, $sqlComplemento)) {
                    gravarLog($sqlComplemento);
                }
            }

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

        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento =  $arrayKit[$i];
        $data_inicio = $arrayInicial[$i];
        $data_fim = $arrayFinal[$i];
        $carga_horario = $horas[$i];

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

        if (mysqli_query($con, $sqlUpdate)) {

            if (isset($arrayInicial) && isset($arrayFinal)) {

                $sqlVerifica = "SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";
                $queryVerifica = mysqli_query($con, $sqlVerifica);
                $parcelas = mysqli_fetch_array($queryVerifica);
                $parcela_id = $parcelas['id'];

                $sqlComplemento = "UPDATE parcela_complementos SET data_inicio = '$data_inicio',  data_fim = '$data_fim ', carga_horaria = '$carga_horario' WHERE parcela_id = '$parcela_id'";

                echo $parcela_id;

                //print_r($sqlComplemento);

                if (mysqli_query($con, $sqlComplemento)) {
                    gravarLog($sqlComplemento);
                }
            }

            gravarLog($sqlUpdate);
        }else {
            echo "Erro ao editar!";
        }
    }
}




