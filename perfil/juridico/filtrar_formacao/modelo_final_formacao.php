<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

$link_vocacional = $http . "formacao_vocacional.php";

isset($_POST['idFormacao']);
$idFormacao = $_POST['idFormacao'];

$amparo = $_POST['amparo'];
$dotacao = $_POST['dotacao'];
$finalizacao = $_POST['finalizar'];


if ($idFormacao == "") {
    $sqlInsert = "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao)
        VALUES ('$idFormacao','$amparo','$finalizacao','$dotacao')";
    $query2 = mysqli_query($con, $sqlInsert);
} else {
    $sqlUptate = "UPDATE juridicos SET pedido_id = $idFormacao, amparo_legal = '$amparo', finalizacao = '$finalizacao', dotacao ='$dotacao'
WHERE pedido_id = $idFormacao";
    $query1 = mysqli_query($con, $sqlUptate);
}
?>


<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <h2 align="center"> Escolha um modelo </h2>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4" align="center">
                        <a href="<?= $link_vocacional ?>" target='_blank' value="<?= $idFormacao ?>">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO FORMAÇÃO
                            </button>
                        </a>
                        <br>
                        <a href="" target='_blank'>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> MANIFESTAÇÃO JURÍDICA
                            </button>
                        </a>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>