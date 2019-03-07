<style>
    .load {
        width: 100px;
        height: 100px;
        position: absolute;
        top: 30%;
        left: 45%;
        color: blue;
    }
</style>
<div class="load"><img src="..\visual\images\loading.gif"></div>
<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['arrayValor'] ?? NULL;
$arrayKit = $_POST['arrayKit'] ?? NULL;

if(isset($arrayValor) && isset($arrayKit)) {

    for($i = 1; $i <= $parcelas; $i++) {
        $valor = $arrayValor[$i];
        $pagamento = $arrayKit[$i];

        $sql = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento, publicado) VALUES ('$idPedido', '$parcelas', '$valor', '$pagamento', 1)";

        if (mysqli_query($con, $sql)){
            gravarLog($sql);
        }
    }
}

//echo "<meta http-equiv='refresh' content='0, url=?perfil=evento&p=pedido_edita'>";
echo "
    <script>
        //swal('Parcelas editadas com sucesso!');
        setTimeout(" . "document.location='?perfil=evento&p=pedido_edita'". ",100000);
    </script>";

