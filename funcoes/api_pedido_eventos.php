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
    }
} else {
    echo false;
}