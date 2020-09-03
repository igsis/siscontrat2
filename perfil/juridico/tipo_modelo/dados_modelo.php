<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}

if (isset($_POST['tipoModelo'])) {
    $modelo = $_POST['tipoModelo'];
}

$idPedido = $_POST['idPedido'];

$link_padraoEvento = $http . "evento_padrao_evento.php";
$link_vocacionalEvento = $http . "evento_vocacional_evento.php";
$link_manifestacaojuridicaEvento = $http . "evento_manifestacao_evento.php";
$link_oficinaEvento = $http . "evento_oficina_evento.php";

$amparo = $_POST['amparo'];
$dotacao = $_POST['dotacao'];
$finalizacao = $_POST['finalizacao'];

$update = "UPDATE pedidos SET status_pedido_id = 15 WHERE id = $idPedido";
if (mysqli_query($con, $update)) {
    $testaEtapa = $con->query("SELECT pedido_id, data_juridico FROM pedido_etapas WHERE pedido_id = $idPedido")->fetch_assoc();
    $data = dataHoraNow();
    if ($testaEtapa == NULL) {
        $insereEtapa = $con->query("INSERT INTO pedido_etapas (pedido_id, data_juridico) VALUES ('$idPedido', '$data')");
    } else if ($testaEtapa != NULL && $testaEtapa['data_juridico'] == "0000-00-00 00:00:00" || $testaEtapa['data_juridico'] != "0000-00-00 00:00:00") {
        $updateEtapa = $con->query("UPDATE pedido_etapas SET data_juridico = '$data' WHERE pedido_id = '$idPedido'");
    }
}

$sql = "SELECT * FROM juridicos where pedido_id = '$idPedido'";
$query = mysqli_query($con, $sql);
$num = mysqli_num_rows($query);
if ($num > 0) {
    $sqlUptate = "UPDATE juridicos SET pedido_id = $idPedido, amparo_legal = '$amparo', finalizacao = '$finalizacao', dotacao ='$dotacao'
    WHERE pedido_id = $idPedido";
    $sqlUptate = mysqli_query($con, $sqlUptate);
} else {
    $sqlInsert = "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao)
        VALUES ('$idPedido','$amparo','$finalizacao','$dotacao')";
    $sqlInsert = mysqli_query($con, $sqlInsert);
}
?>


<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <h2 align="center"> Escolha um modelo </h2>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4" align="center">
                        <form action="<?= $link_padraoEvento ?>" method="post" role="form" target="_blank">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                            <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO PADRÃO
                            </button>
                        </form>
                        <br>
                        <form action="<?= $link_vocacionalEvento ?>" method="post" role="form" target="_blank">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                            <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO FORMAÇÃO
                            </button>
                        </form>
                        <br>
                        <form action="<?= $link_manifestacaojuridicaEvento ?>" method="post" role="form"
                              target="_blank">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                            <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> MANIFESTAÇÃO JURÍDICA
                            </button>
                        </form>
                        <br>
                        <form action="<?= $link_oficinaEvento ?>" method="post" role="form" target="_blank">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                            <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO OFICINAS
                            </button>
                        </form>
                    </div>
                </div>
                <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="post" role="form">
                    <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                    <input type="hidden" value="<?= $modelo ?>" name="tipoModelo">
                    <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                    <input type="hidden" value="<?= $dotacao ?>" name="dotacao">
                    <button type="submit" name="voltar" class="btn btn-default pull-left"> Voltar</button>
                </form>
            </div>
        </div>
    </section>
</div>