<?php
require_once 'funcoesConecta.php';
require_once 'funcoesGerais.php';
$con = bancoMysqli();

if (isset($_POST['_method'])) {
    session_start();
    switch ($_POST['_method']){
        case "valorPorEquipamento":
            $valoresEquipamentos = $_POST['valorEquipamento'];
            $equipamentos = $_POST['equipamentos'];
            $idPedido = $_SESSION['idPedido'];

            $sql_delete = "DELETE FROM valor_equipamentos WHERE pedido_id = '$idPedido'";
            mysqli_query($con, $sql_delete);

            for ($i = 0; $i < count($valoresEquipamentos); $i++){
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
        default:
            echo false;
            break;
    }
} else {
    echo false;
}