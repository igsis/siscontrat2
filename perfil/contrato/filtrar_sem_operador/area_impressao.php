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

$link_reserva_vocacional = $http . "impressao_reserva_vocacional.php";

$link_reserva_sme = $http . "impressao_reserva_sme.php";

$link_reserva_pia = $http . "impressao_reserva_pia.php";

$link_proposta_convenio = $http . "rlt_proposta_oficina_convenio.php";

$link_reversao = $http . "rlt_reversao_proposta.php";

$link_direitos = $http . "rlt_direitos_conexos.php";

$link_convenio_pf = $http . "rlt_convenio500_pf.php";

$link_convenio_pj = $http . "rlt_convenio500_pj.php";

$link_exclusividade_pf = $http . "rlt_exclusividade_pf.php";

$link_exclusividade_pj = $http . "rlt_exclusividade_pj.php";

$link_condicionamento_pf = $http . "rlt_condicionamento_pf.php";

$link_condicionamento_pj = $http . "rlt_condicionamento_pj.php";

$link_facc_pf = $http . "rlt_fac_pf.php";

$link_facc_pj = $http . "rlt_fac_pj.php";

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);

if ($pedido['pessoa_tipo_id'] == 1) {
    $link_pc = $linkpf_pc;
    $link_edital = $linkpf_edital;
    $link_convenio = $link_convenio_pf;
    $link_exclusividade = $link_exclusividade_pf;
    $link_condicionamento = $link_condicionamento_pf;
    $link_facc = $link_facc_pf;
    $idPessoa = $pedido['pessoa_fisica_id'];
} else if ($pedido['pessoa_tipo_id'] == 2) {
    $link_pc = $linkpj_pc;
    $link_edital = $linkpj_edital;
    $link_convenio = $link_convenio_pj;
    $link_exclusividade = $link_exclusividade_pj;
    $link_condicionamento = $link_condicionamento_pj;
    $link_facc = $link_facc_pj;
    $idPessoa = $pedido['pessoa_juridica_id'];
}

?>
<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header"> Área de Impressão </h3>
        <div class="box">
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
                            <div class="col-md-12">
                                <form action="<?= $link_pcf ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Pedido de Contratação - Formação
                                    </button>
                                </form>
                                <hr/>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-12">
                                <form action="<?= $link_pc ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Pedido de Contratação
                                    </button>
                                </form>
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
                    <?php
                    if ($pedido['origem_tipo_id'] == 2){ ?>
                    <div class="col-md-6">
                        <form action="<?= $link_vocacional ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Vocacional
                            </button>
                        </form>
                        <hr/>
                        <?php } else{
                        ?>
                        <div class="col-md-6">
                            <form action="<?= $link_proposta_padrao ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Vocacional
                                </button>
                            </form>
                            <hr/>
                            <?php } ?>

                            <form action="<?= $link_edital ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Editais
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_proposta_padrao ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Contratações gerais - Com cachê
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_proposta_convenio ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Proposta Oficinas / Convênio MINC
                                </button>
                            </form>
                            <hr/>
                        </div>
                        <?php
                        if ($pedido['origem_tipo_id'] == 2){ ?>
                        <div class="col-md-6">
                            <form action="<?= $link_vocacional ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    PIÁ
                                </button>
                            </form>
                            <hr/>
                            <?php } else{
                            ?>
                            <div class="col-md-6">
                                <form action="<?= $link_proposta_padrao ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        PIÁ
                                    </button>
                                </form>
                                <hr/>
                                <?php }

                                if ($pedido['origem_tipo_id'] == 3) {
                                    ?>
                                    <form action="<?= $link_emia ?>" target="_blank" method="post">
                                        <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                        <button type="submit" class="btn btn-outline-info center-block">
                                            EMIA
                                        </button>
                                    </form>
                                    <hr/>
                                <?php } ?>

                                <form action="<?= $link_reversao ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Contratações gerais - Reversão de Bilheteria
                                    </button>
                                </form>
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
                                <form action="<?= $link_direitos ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Direitos Conexos
                                    </button>
                                </form>
                                <hr/>

                                <form action="<?= $link_convenio ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Convênio 500
                                    </button>
                                </form>
                                <hr/>
                            </div>

                            <div class="col-md-6">
                                <form action="<?= $link_exclusividade ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Exclusividade
                                    </button>
                                </form>
                                <hr/>

                                <form action="<?= $link_condicionamento ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Condicionamento
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
                            </div>

                            <div class="col-md-6">
                                <form action="<?= $link_facc . "?id=" . $idPessoa ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        FACC
                                    </button>
                                </form>
                                <hr/>
                            </div>

                            <div class="col-md-6">
                                <form action="#" type="submit" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        Parecer da Comissão
                                    </button>
                                </form>
                                <hr/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <form action="#" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                    <button type="submit" class="btn btn-outline-info center-block">
                                        NORMAS INTERNAS - Teatros Municipais
                                    </button>
                                </form>
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
                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    FORMAÇÃO - Vocacional
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    VOCACIONAL/PIÁ - SME
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva CSMB
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva CCSP
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Oficina
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva Gabinete
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva DPH
                                </button>
                            </form>
                            <hr/>
                        </div>

                        <div class="col-md-6">
                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    FORMAÇÃO - PIÁ
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva Centros Culturais
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva Casas de Cultura
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva BMA
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva Oficinas CSMB e BMA
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva Global
                                </button>
                            </form>
                            <hr/>

                            <form action="#" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva DPH - Jornada do Patrimônio
                                </button>
                            </form>
                            <hr/>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=contrato&p=filtrar_sem_operador&sp=pesquisa_contratos">
                            <button type="submit" class="btn btn-default">Voltar</button>
                        </a>
                    </div>
                </div>
    </section>
</div>

