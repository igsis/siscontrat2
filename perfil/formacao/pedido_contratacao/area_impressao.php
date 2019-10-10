<?php
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";

$link_pcf = $http . "impressao_pedido_formacao.php";

$link_vocacional = $http . "rlt_proposta_formacao.php";

$link_facc = $http . "rlt_fac_pf.php";

$link_reserva_vocacional = $http . "impressao_reserva_vocacional.php";

$link_reserva_sme = $http . "impressao_reserva_sme.php";

$link_reserva_pia = $http . "impressao_reserva_pia.php";

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
?>
<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header"> Área de Impressão </h3>
        <div class="box box-primary">
            <div class="box-header">
                <h4 align="center">
                    Qual modelo de documento deseja imprimir?
                </h4>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">PEDIDO</h4>
                        </nav>
                        <a href="<?= $link_pcf ?>" target="_blank" type="button">
                            <h4 align="center">Pedido de Contratação - Formação</h4>
                        </a>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">PROPOSTA</h4>
                        </nav>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= $link_vocacional ?>" target="_blank" type="button">
                            <h4 align="center">Vocacional</h4>
                        </a>
                        <hr/>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= $link_vocacional ?>" target="_blank" type="button">
                            <h4 align="center">PIÁ</h4>
                        </a>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">OUTROS</h4>
                        </nav>
                        <a href="<?= $link_facc . "?id=" . $idPf ?>" target="_blank" type="button">
                            <h4 align="center">FACC</h4>
                        </a>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">PEDIDO DE RESERVA</h4>
                        </nav>
                    </div>

                    <div class="col-md-6">
                        <a href="<?= $link_reserva_vocacional ?>" target="_blank" type="button">
                            <h4 align="center">FORMAÇÃO - Vocacional</h4>
                        </a>
                        <hr/>
                        <a href="<?= $link_reserva_sme ?>" target="_blank" type="button">
                            <h4 align="center">VOCACIONAL/PIÁ - SME</h4>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="<?= $link_reserva_pia ?>" target="_blank" type="button">
                            <h4 align="center">FORMAÇÃO - PIÁ</h4>
                            <hr/>
                        </a>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=formacao&p=pedido_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>

    </section>
</div>
