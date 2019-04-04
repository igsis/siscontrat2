<?php
$con = bancoMysqli();
$conn = bancoPDO();

$idPedido = $_SESSION['idPedido'];

$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['valores'] ?? [];
$arrayKit = $_POST['datas'] ?? [];

$sqlVerifica = "SELECT id FROM parcelas WHERE pedido_id = '$idPedido'";
$queryVerifica = mysqli_query($con, $sqlVerifica);
$nums = mysqli_num_rows($queryVerifica);

//oficinas
$arrayInicial = $_POST['arrayInicial'] ?? NULL;
$arrayFinal = $_POST['arrayFinal'] ?? NULL;
$horas = $_POST['horas'] ?? NULL;

if ($nums < $parcelas) {
    echo "teste 1";

    for ($i = 1; $i <= $parcelas; $i++) {
        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento = $arrayKit[$i];

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

        if (mysqli_query($con, $sqlUpdate)) {

            if (isset($arrayInicial) && isset($arrayFinal)) {
                echo "caiu";

                $parcela_id = $parcelasSalvas[$i]['id'];

                echo "parcela id " . $parcela_id;

                $sqlComplemento = "UPDATE parcela_complementos SET data_inicio = '$arrayInicial[$i]',  data_fim = '$arrayFinal[$i]', carga_horaria = '$horas[$i]' 
                                      WHERE parcela_id = '$parcela_id'";

                if (mysqli_query($con, $sqlComplemento)) {
                    gravarLog($sqlComplemento);
                }
            }
            gravarLog($sqlUpdate);
        } else {
            echo "Erro ao editar!";
        }
    }

    $faltando = $parcelas - $nums;
    $count = $nums + 1;

    for ($i = 1; $i <= $faltando; $i++) {

        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento = $arrayKit[$i];

        $sqlInsert = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento, publicado) VALUES ('$idPedido', '$count', '$valor', '$dataPagamento', 1)";

        echo $sqlInsert;

        if (mysqli_query($con, $sqlInsert)) {
            $sqlPedido = "UPDATE pedidos SET numero_parcelas = '$parcelas'";
            if (mysqli_query($con, $sqlPedido)) {
                $parcela = recuperaUltimo("parcelas");

                if (isset($arrayInicial) && isset($arrayFinal)) {
                    $sqlComplemento = "INSERT INTO parcela_complementos (parcela_id, data_inicio, data_fim, carga_horaria, publicado) VALUES ('$parcela', '$arrayInicial[$i]', '$arrayFinal[$i]', '$horas[$i]', 1)";

                    echo "caiu" . $sqlComplemento;
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
        $count++;
    }

} elseif ($nums > $parcelas) {

    echo "teste 2";

    $sobrando = $nums - $parcelas;

    for ($i = 1; $i <= $parcelas; $i++) {

        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento = $arrayKit[$i];

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

        if (mysqli_query($con, $sqlUpdate)) {

            if (isset($arrayInicial) && isset($arrayFinal)) {
                $x = 1;
                while ($parcelasSalvas = mysqli_fetch_array($queryVerifica)) {
                    $data_inicio = $arrayInicial[$x];
                    $data_fim = $arrayFinal[$x];
                    $horas = $horas[$x];
                    $parcela_id = $parcelasSalvas['id'];

                    $sqlComplemento = "UPDATE parcela_complementos SET data_inicio = '$data_inicio',  data_fim = '$data_fim', carga_horaria = '$horas' WHERE parcela_id = '$parcela_id'";

                    if (mysqli_query($con, $sqlComplemento)) {
                        gravarLog($sqlComplemento);
                    }

                    if ($nums > $parcelas) {
                        continue;
                    }

                    echo $sqlComplemento;

                    print_r($parcelasSalvas);

                    echo "teste " . $parcela_id;

                    $x++;

                }
            }
            gravarLog($sqlUpdate);
        } else {
            echo "Erro ao editar!";
        }
    }

    $count = $parcelas + 1;

    for ($i = 1; $i <= $sobrando; $i++) {
        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento = $arrayKit[$i];

        echo " count = " . $count;

        if (isset($arrayInicial) && isset($arrayFinal)) {
            $sqlTeste = "SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$count'";
            $query = mysqli_query($con, $sqlTeste);
            $Parcela = mysqli_fetch_array($query);
            $idParcela = $Parcela['id'];

            $sqlComplemento = "DELETE FROM parcela_complementos WHERE parcela_id = '$idParcela'";

            echo $sqlComplemento;

            if (mysqli_query($con, $sqlComplemento)) {
                gravarLog($sqlComplemento);

                $sqlDelete = "DELETE FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$count'";

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
            
            $sqlDelete = "DELETE FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$count'";

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
        $count++;
    }

} else {

    for ($i = 1; $i <= $parcelas; $i++) {

        $valor = dinheiroDeBr($arrayValor[$i]);
        $dataPagamento = $arrayKit[$i];

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";

        if (mysqli_query($con, $sqlUpdate)) {

            if (isset($arrayInicial) && isset($arrayFinal)) {
                $data_inicio = $arrayInicial[$i];
                $data_fim = $arrayFinal[$i];
                $carga_horario = $horas[$i];

                $sqlVerifica = "SELECT id FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";
                $queryVerifica = mysqli_query($con, $sqlVerifica);
                $parcelas = mysqli_fetch_array($queryVerifica);
                $parcela_id = $parcelas['id'];

                $sqlComplemento = "UPDATE parcela_complementos SET data_inicio = '$data_inicio',  data_fim = '$data_fim ', carga_horaria = '$carga_horario' WHERE parcela_id = '$parcela_id'";

                //print_r($sqlComplemento);

                if (mysqli_query($con, $sqlComplemento)) {
                    gravarLog($sqlComplemento);
                }
            }

            gravarLog($sqlUpdate);
        } else {
            echo "Erro ao editar!";
        }
    }
}




