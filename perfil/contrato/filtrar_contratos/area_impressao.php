<?php
$con = bancoMysqli();

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
$sqlTesta = "SELECT * FROM pedidos WHERE origem_tipo_id = 2";
$queryTesta = mysqli_query($con, $sqlTesta);
$row = mysqli_num_rows($queryTesta);
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
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a href="#" target="_blank" type="button">
                                <h4 align="center">Pedido de Contratação</h4>
                                <hr/>
                            </a>
                        </div>
                        <?php
                        if ($row == 0) { ?>
                            <div class="col-md-6">
                                <h4 align="center">Pedido de Contratação - Formação</h4>
                                <hr/>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-6">
                                <a href="<?= $link_pcf ?>" target="_blank" type="button">
                                    <h4 align="center">Pedido de Contratação - Formação</h4>
                                </a>
                                <hr/>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">PROPOSTA</h4>
                        </nav>
                    </div>

                    <div class="col-md-6">
                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Vocacional</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Editais</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Contratações gerais - Com cachê</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Proposta Oficinas / Convênio MINC</h4>
                        </a>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <a href="#" target="_blank" type="button">
                            <h4 align="center">PIÁ</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">EMIA</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Contratações gerais - Reversão de Bilheteria</h4>
                        </a>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">DECLARAÇÃO</h4>
                        </nav>
                    </div>

                    <div class="col-md-6">
                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Direitos Conexos</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Convênio 500</h4>
                        </a>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Exclusividade</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Condicionamento</h4>
                        </a>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">OUTROS</h4>
                        </nav>
                    </div>

                    <div class="col-md-6">
                        <a href="#" target="_blank" type="button">
                            <h4 align="center">FACC</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">NORMAS INTERNAS - Teatros Municipais</h4>
                        </a>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <a href="#" type="button" target="_blank">
                            <h4 align="center">Parecer da Comissão</h4>
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
                        <a href="#" target="_blank" type="button">
                            <h4 align="center">FORMAÇÃO - Vocacional</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">VOCACIONAL/PIÁ - SME</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva CSMB</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva CCSP</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Oficina</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva Gabinete</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva DPH</h4>
                        </a>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <a href="#" target="_blank" type="button">
                            <h4 align="center">FORMAÇÃO - PIÁ</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva Centros Culturais</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva Casas de Cultura</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva BMA</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva Oficinas CSMB e BMA</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva Global</h4>
                        </a>
                        <hr/>

                        <a href="#" target="_blank" type="button">
                            <h4 align="center">Reserva DPH - Jornada do Patrimônio</h4>
                        </a>
                        <hr/>
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

