<?php
$con = bancoMysqli();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";

$link_pcf = $http . "impressao_pedido_formacao.php";

$linkpf_pc = $http . "impressao_pedido_pf.php";

$linkpj_pc = $http . "impressao_pedido_pj.php";

$link_vocacional = $http . "rlt_proposta_formacao.php";

$linkpf_edital = $http . "exporta_proposta_edital_pf_word.php";

$linkpj_edital = $http . "exporta_proposta_edital_pj_word.php";

$link_emia = $http . "rlt_proposta_emia.php";

$link_proposta_padrao = $http . "rlt_proposta_padrao.php";

$link_facc = $http . "rlt_fac_pf.php";

$link_reserva_vocacional = $http . "impressao_reserva_vocacional.php";

$link_reserva_sme = $http . "impressao_reserva_sme.php";

$link_reserva_pia = $http . "impressao_reserva_pia.php";

$link_proposta_convenio = $http . "rlt_proposta_oficina_convenio.php";

$idPedido = $_SESSION['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);

$idPf = $pedido['pessoa_fisica_id'];

if ($pedido['pessoa_tipo_id'] == 1) {
    $link_pc = $linkpf_pc;
    $link_edital = $linkpf_edital;
} else if ($pedido['pessoa_tipo_id'] == 2) {
    $link_pc = $linkpj_pc;
    $link_edital = $linkpj_edital;
}

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
                        <?php
                        if ($pedido['origem_tipo_id'] == 2) { ?>
                            <div class="col-md-6">
                                <a href="<?= $link_pc ?>" target="_blank">
                                    <button type="button" class="btn btn-outline-info center-block">
                                        Pedido de Contratação
                                    </button>
                                </a>
                                <hr/>
                            </div>

                            <div class="col-md-6">
                                <a href="<?= $link_pcf ?>" target="_blank">
                                    <button type="button" class="btn btn-outline-info center-block">
                                        Pedido de Contratação - Formação
                                    </button>
                                </a>
                                <hr/>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-12">
                                <a href="<?= $link_pc ?>" target="_blank">
                                    <button type="button" class="btn btn-outline-info center-block">
                                        Pedido de Contratação
                                    </button>
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
                        <a href="<?= $link_vocacional ?>" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Vocacional
                            </button>
                        </a>
                        <hr/>

                        <a href="<?= $link_edital ?>" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Editais
                            </button>
                        </a>
                        <hr/>

                        <a href="<?= $link_proposta_padrao ?>" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Contratações gerais - Com cachê
                            </button>
                        </a>
                        <hr/>

                        <a href="<?= $link_proposta_convenio ?>" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Proposta Oficinas / Convênio MINC
                            </button>
                        </a>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <a href="<?= $link_vocacional ?>" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                PIÁ
                            </button>
                        </a>
                        <hr/>
                        <?php
                        if ($pedido['origem_tipo_id'] == 3) {
                            ?>
                            <a href="<?= $link_emia ?>" target="_blank">
                                <button type="button" class="btn btn-outline-info center-block">
                                    EMIA
                                </button>
                            </a>
                            <hr/>
                        <?php } ?>

                        <a href="#" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Contratações gerais - Reversão de Bilheteria
                            </button>
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
                        <a href="#" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Direitos Conexos
                            </button>
                        </a>
                        <hr/>

                        <a href="#" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Convênio 500
                            </button>
                        </a>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <a href="#" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Exclusividade
                            </button>
                        </a>
                        <hr/>

                        <a href="#" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Condicionamento
                            </button>
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
                        <a href="#" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                FACC
                            </button>
                        </a>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <a href="#" type="button" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                Parecer da Comissão
                            </button>
                        </a>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <a href="#" target="_blank">
                            <button type="button" class="btn btn-outline-info center-block">
                                NORMAS INTERNAS - Teatros Municipais
                            </button>
                        </a>
                        <hr/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-static-top bg-light-blue-active">
                        <h4 align="center">PEDIDO DE RESERVA</h4>
                    </nav>
                </div>

                <div class="col-md-6">
                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            FORMAÇÃO - Vocacional
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            VOCACIONAL/PIÁ - SME
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva CSMB
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva CCSP
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Oficina
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva Gabinete
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva DPH
                        </button>
                    </a>
                    <hr/>
                </div>

                <div class="col-md-6">
                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            FORMAÇÃO - PIÁ
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva Centros Culturais
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva Casas de Cultura
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva BMA
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva Oficinas CSMB e BMA
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva Global
                        </button>
                    </a>
                    <hr/>

                    <a href="#" target="_blank">
                        <button type="button" class="btn btn-outline-info center-block">
                            Reserva DPH - Jornada do Patrimônio
                        </button>
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

