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
                    <button type="submit" name="voltar" class="btn btn-default pull-left"> Voltar</button>
                </form>
            </div>
        </div>
    </section>
</div>