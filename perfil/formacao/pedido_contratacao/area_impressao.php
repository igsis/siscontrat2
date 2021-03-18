<?php
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";

$link_pcf = $http . "impressao_pedido_formacao.php";

$link_vocacional = $http . "rlt_proposta_formacao.php";

$link_facc = $http . "rlt_fac_pf.php";

$link_reserva = $http . "rlt_reserva_formacao.php";

$link_despacho = $http . "rlt_despacho_formacao.php";

$idPedido = $_POST['idPedido'];
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
                        <form action="<?= $link_pcf ?>" target="_blank" method="POST">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Pedido de Contratação - Formação
                            </button>
                        </form>
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
                        <form action="<?= $link_vocacional ?>" target="_blank" method="POST">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Vocacional
                            </button>
                        </form>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <form action="<?= $link_vocacional ?>" target="_blank" method="POST">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                PIÁ
                            </button>
                        </form>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">OUTROS</h4>
                        </nav>
                        <form action="<?= $link_facc ?>" target="_blank" method="POST">
                            <input type="hidden" value="<?=$idPf?>" name="idPf">
                            <button type="submit" style="color:black;" class="btn btn-outline-info center-block">
                                FACC
                            </button>
                        </form>
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
                        <form action="<?= $link_reserva ?>" target="_blank" method="POST">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="tipo" value="vocacional">
                            <button type="submit" class="btn btn-outline-info center-block">
                                FORMAÇÃO - Vocacional
                            </button>
                        </form>
                        <hr/>
                        <form action="<?= $link_reserva ?>" target="_blank" method="POST">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="tipo" value="sme">
                            <button type="submit" class="btn btn-outline-info center-block">
                                VOCACIONAL/PIÁ - SME
                            </button>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <form action="<?= $link_reserva ?>" target="_blank" method="POST">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="tipo" value="pia">
                            <button type="submit" class="btn btn-outline-info center-block">
                                FORMAÇÃO - PIÁ
                            </button>
                        </form>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">DESPACHO</h4>
                        </nav>
                    </div>

                    <div class="col-md-12">
                        <form action="<?= $link_despacho ?>" target="_blank" method="POST">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Vocacional/PIÁ
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            <div class="box-footer">
                <form action="?perfil=formacao&p=pedido_contratacao&sp=edita" method="POST">
                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                    <button type="submit" name="carregar" class="btn btn-default">Voltar</button>
                </form>
            </div>
        </div>
    </section>
</div>
