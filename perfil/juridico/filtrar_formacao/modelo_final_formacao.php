<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";
$http = $server . "/pdf/";

$link_vocacional = $http . "formacao_vocacional.php";
$link_pia = $http . "formacao_pia.php";

if (isset($_POST['doc'])) {
    $idFormacao = $_POST['idFormacao'];
    $idPedido = $_POST['idPedido'];
    $tipoModelo = $_POST['tipoModelo'];
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <h2 align="center"> Escolha um modelo </h2>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4" align="center">
                        <form action="<?= $link_vocacional ?>" method="post" target="_blank">
                            <input type="hidden" value="<?= $idFormacao ?>" name="idFormacao">
                            <input type='hidden' name='idPedido' value='<?= $idPedido ?>'>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO VOCACIONAL
                            </button>
                        </form>
                        <form action="<?= $link_pia ?>" method="post" target="_blank">
                            <input type="hidden" value="<?= $idFormacao ?>" name="idFormacao">
                            <input type='hidden' name='idPedido' value='<?= $idPedido ?>'>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"> DESPACHO PIÁ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <form action="?perfil=juridico&p=filtrar_formacao&sp=resumo_formacao" method="post">
                    <input type="hidden" value="<?= $idFormacao ?>" name="idFormacao">
                    <input type="hidden" name="tipoModelo" value="<?= $tipoModelo ?>">
                    <button type="submit" class="btn btn-default"> Voltar
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>