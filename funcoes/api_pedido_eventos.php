<?php
require_once 'funcoesConecta.php';
require_once 'funcoesGerais.php';
$con = bancoMysqli();

if (isset($_POST['_method'])) {
    session_start();
    switch ($_POST['_method']) {
        case "valorPorEquipamento":
            $valoresEquipamentos = $_POST['valorEquipamento'];
            $equipamentos = $_POST['equipamentos'];
            $idPedido = $_POST['idPedido'];

            $sql_delete = "DELETE FROM valor_equipamentos WHERE pedido_id = '$idPedido'";
            mysqli_query($con, $sql_delete);

            for ($i = 0; $i < count($valoresEquipamentos); $i++) {
                $valor = dinheiroDeBr($valoresEquipamentos[$i]);
                $idLocal = $equipamentos[$i];

                $sql_insert_valor = "INSERT INTO valor_equipamentos (local_id, pedido_id, valor) 
                             VALUES ('$idLocal', '$idPedido', '$valor')";

                if (mysqli_query($con, $sql_insert_valor)) {
                    $erro[] = false;
                } else {
                    $erro[] = true;
                }
            }
            echo in_array(true, $erro, true) ? false : true;
            break;

        case "parecerArtistico":
            $idPedido = $_POST['idPedido'];

            $topico1 = addslashes($_POST['topico1']);
            $topico2 = addslashes($_POST['topico2']);
            $topico3 = addslashes($_POST['topico3']);
            $topico4 = addslashes($_POST['topico4']);

            $sql_cadastra = "INSERT INTO parecer_artisticos (pedido_id, topico1, topico2, topico3, topico4) VALUES ('$idPedido','$topico1','$topico2','$topico3','$topico4')
                                 ON DUPLICATE KEY UPDATE topico1 = '$topico1', topico2 = '$topico2', topico3 = '$topico3', topico4 = '$topico4'";
            if (mysqli_query($con, $sql_cadastra)) {
                echo true;
            } else {
                echo false;
            }
            break;
        case "parcelas":

            $idVerba = $_POST["verba_id"];
            $valor_total = dinheiroDeBr($_POST["valor_total"]);
            $num_parcelas = $_POST["numero_parcelas"];
            $forma_pagamento = trim(addslashes($_POST["forma_pagamento"]));
            $justificativa = trim(addslashes($_POST["justificativa"]));
            $observacao = trim(addslashes($_POST["observacao"]));
            $idPedido = $_POST["idPedido"];
            $tipoPesso = $_POST["tipoPessoa"];
            $idProponent = $_POST["idProponente"];
            $data_kit_pagamento = $_POST["data_kit"];

            if ($num_parcelas == 1 || $num_parcelas == 13) {
                $data_kit_pagamento = date('Y-m-d', strtotime("+1 days", strtotime($data_kit_pagamento)));
            }else{
                $queryParcela = "SELECT data_pagamento FROM parcelas WHERE pedido_id = ".$idPedido." AND numero_parcelas = 1";
                $data_kit_pagamento = mysqli_fetch_row(mysqli_query($con,$queryParcela))[0];
            }
             $query = "UPDATE pedidos SET verba_id = '$idVerba', numero_parcelas = '$num_parcelas', valor_total = '$valor_total', forma_pagamento = '$forma_pagamento', data_kit_pagamento = '$data_kit_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
            if (mysqli_query($con,$query)){
                echo true;
            }else{
                echo false;
            }

            break;

        default:
            echo false;
            break;
    }
} else {
    echo false;
}