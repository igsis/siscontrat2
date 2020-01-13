<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

$link_padraoEvento = $http . "evento_padrao_evento.php";
$link_vocacionalEvento = $http . "evento_vocacional_evento.php";
$link_manifestacaojuridicaEvento = $http . "evento_manifestacao_evento.php";
$link_oficinaEvento = $http . "evento_oficina_evento.php";

$amparo = $_POST['amparo'];
$idEvento = $_SESSION['eventoId'];
$dotacao = $_POST['dotacao'];
$finalizacao = $_POST['finalizar'];


$sql = "SELECT * FROM juridicos where pedido_id = '$idEvento'";
$query = mysqli_query($con, $sql);
$num = mysqli_num_rows($query);

if ($num > 0) {
    $sqlUptate = "UPDATE juridicos SET pedido_id = $idEvento, amparo_legal = '$amparo', finalizacao = '$finalizacao', dotacao ='$dotacao'
    WHERE pedido_id = $idEvento";
    $sqlUptate = mysqli_query($con, $sqlUptate);
} else {
    $sqlInsert = "INSERT INTO juridicos(pedido_id, amparo_legal, finalizacao, dotacao)
        VALUES ('$idEvento','$amparo','$finalizacao','$dotacao')";
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
                        <a href="<?= $link_padraoEvento ?>" target="_blank">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO PADRÃO
                            </button>
                        </a>
                        <br>
                        <a href="<?= $link_vocacionalEvento ?>" target='_blank'>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO FORMAÇÃO
                            </button>
                        </a>
                        <br>
                        <a href="<?= $link_manifestacaojuridicaEvento ?>" target='_blank'>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> MANIFESTAÇÃO JURÍDICA
                            </button>
                        </a>
                        <br>
                        <a href="<?= $link_oficinaEvento ?>" target='_blank'>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO OFICINAS
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>