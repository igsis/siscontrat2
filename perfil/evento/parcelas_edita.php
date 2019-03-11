<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$parcelas = $_POST['parcelas'] ?? NULL;
$valores = $_POST['arrayValor'] ?? NULL;
$datasPagamento = $_POST['arrayKit'] ?? NULL;


if (isset($valores) && isset($datasPagamento)) {

    for ($i = 1; $i <= count($parcelas); $i++) {
        $parcela = $parcelas[$i];
        $valor = dinheiroParaBr($valores[$i]);
        $dataPagamento = $datasPagamento[$i];

        $sqlUpdate = "UPDATE parcelas SET valor = '$valor', data_pagamento = '$dataPagamento' WHERE pedido_id = '$idPedido' AND numero_parcelas = '$parcela'";

        echo $sqlUpdate;

        if (mysqli_query($con, $sqlUpdate)) {
            gravarLog($sqlUpdate);

            echo "
                <script>
                    setTimeout(" . "document.location='?perfil=evento&p=pedido_edita'" . ",0);
                </script>";
        }
    }
}



