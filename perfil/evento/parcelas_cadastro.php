<?php
$con = bancoMysqli();

$idPedido = $_SESSION['idPedido'];
$parcelas = $_POST['parcelas'] ?? NULL;
$arrayValor = $_POST['arrayValor'] ?? NULL;
$arrayKit = $_POST['arrayKit'] ?? NULL;

if (isset($arrayValor) && isset($arrayKit)) {

    for ($i = 1; $i <= $parcelas; $i++) {
        $valor = dinheiroDeBr($arrayValor[$i]);
        $pagamento = $arrayKit[$i];

        $sql = "INSERT INTO parcelas (pedido_id, numero_parcelas, valor, data_pagamento, publicado) VALUES ('$idPedido', '$i', '$valor', '$pagamento', 1)";

        if (mysqli_query($con, $sql)) {
            gravarLog($sql);
        }
    }
}

//echo "<meta http-equiv='refresh' content='0, url=?perfil=evento&p=pedido_edita'>";
echo "
    <script>
        setTimeout(" . "document.location='?perfil=evento&p=pedido_edita'" . ",0);
    </script>";

?>

<script type="text/javascript">
    window.onload=function(){


    }
</script>



<!--<style>
    .load {
        width: 100px;
        height: 100px;
        position: absolute;
        left: 40%;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Salvando parcelas</h2>
        <div class="row" align="center">
             /*if (isset($mensagem)) {
                echo $mensagem;
            }; */
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edição de parcelas</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="load">
                                <img src="..\visual\images\loading1.gif"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
-->


