<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

$link_vocacional = $http . "formacao_vocacional.php";
$link_pia = $http . "formacao_pia.php";


isset($_POST['idFormacao']);
$idFormacao = $_POST['idFormacao'];

if(isset($_POST['enviar'])) {
    $dotacao = $_POST['dotacao'];
}


$sql = "SELECT * FROM juridicos where pedido_id = '$idFormacao'";
$query = mysqli_query($con,$sql);
$num = mysqli_num_rows($query);

if ($num > 0) {
    $sqlUpdate = "UPDATE juridicos SET pedido_id = $idFormacao,dotacao ='$dotacao'
    WHERE pedido_id = $idFormacao";
    $query = mysqli_query($con,$sqlUpdate);
}
else {
    $sqlInsert = "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao)
        VALUES ('$idFormacao','','','$dotacao')";
    $query = mysqli_query($con,$sqlInsert);

}
?>


<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <h2 align="center"> Escolha um modelo </h2>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4" align="center">
                        <form action="<?= $link_vocacional ?>" method="post">
                        <input type="hidden" value="<?= $idFormacao ?>" name="idFormacao">
                        <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO VOCACIONAL
                        </button>
                        </form>
                        <form action="<?= $link_pia ?>" method="post">
                            <input type="hidden" value="<?= $idFormacao ?>" name="idFormacao">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO PIÁ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>