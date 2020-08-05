<?php
$con = bancoMysqli();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";

$linkpf_pc = $http . "impressao_pedido_pf.php";

$linkpj_pc = $http . "impressao_pedido_pj.php";

$linkpf_edital = $http . "exporta_proposta_edital_pf_word.php?penal=";

$linkpj_edital = $http . "exporta_proposta_edital_pj_word.php?penal=";

$link_emia = $http . "rlt_proposta_emia.php";

$link_proposta_padrao_pf = $http . "rlt_proposta_padrao_pf.php?penal=";

$link_proposta_padrao_pj = $http . "rlt_proposta_padrao_pj.php?penal=";

$link_reserva_vocacional = $http . "impressao_reserva_vocacional.php";

$link_reserva_sme = $http . "impressao_reserva_sme.php";

$link_reserva_pia = $http . "impressao_reserva_pia.php";

$link_proposta_convenio = $http . "rlt_proposta_oficina_convenio.php?penal=";

$link_reversao_pf = $http . "rlt_reversao_proposta_pf.php?penal=";

$link_reversao_pj = $http . "rlt_reversao_proposta_pj.php?penal=";

$link_direitos = $http . "rlt_direitos_conexos.php";

$link_convenio_pf = $http . "rlt_convenio500_pf.php";

$link_convenio_pj = $http . "rlt_convenio500_pj.php";

$link_exclusividade_pf = $http . "rlt_exclusividade_pf.php";

$link_exclusividade_pj = $http . "rlt_exclusividade_pj.php";

$link_condicionamento_pf = $http . "rlt_condicionamento_pf.php";

$link_condicionamento_pj = $http . "rlt_condicionamento_pj.php";

$link_facc_pf = $http . "rlt_fac_pf.php";

$link_facc_pj = $http . "rlt_fac_pj.php";

$link_parecer_pf = $http . "rlt_parecer_pf.php";

$link_parecer_pj = $http . "rlt_parecer_pj.php";

$link_normas_pf = $http . "rlt_normas_internas_teatros_pf.php";

$link_normas_pj = $http . "rlt_normas_internas_teatros_pj.php";

$link_casa_cultura_pf = $http . "rlt_casa_cultura_pf.php";

$link_casa_cultura_pj = $http . "rlt_casa_cultura_pj.php";

$link_vai_pf = $http . "rlt_programa_vai_pf.php";

$link_vai_pj = $http . "rlt_programa_vai_pj.php";

$link_fepac = $http . "rlt_fepac.php";

$link_reserva_global = $http . "rlt_reserva_global.php";

$link_centros_culturais_6354_pf = $http . "rlt_centros_culturais_6354_pf.php";

$link_centros_culturais_6354_pj = $http . "rlt_centros_culturais_6354_pj.php";

$link_reserva_ccsp_6393_pf = $http . "rlt_reserva_ccsp_6393_pf.php";

$link_reserva_ccsp_6393_pj = $http . "rlt_reserva_ccsp_6393_pj.php";

$link_reserva_oficina_pf = $http . "rlt_reserva_oficina_pf.php";

$link_reserva_oficina_pj = $http . "rlt_reserva_oficina_pj.php";

$link_reserva_csmb_pf = $http . "rlt_reserva_csmb_pf.php";

$link_reserva_csmb_pj = $http . "rlt_reserva_csmb_pj.php";

$link_reserva_dph_pf = $http . "rlt_reserva_gabinete_dph_pf.php";

$link_reserva_dph_pj = $http . "rlt_reserva_gabinete_dph_pj.php";

$link_reserva_gabinete_pf = $http . "rlt_reserva_gabinete_pf.php";

$link_reserva_gabinete_pj = $http . "rlt_reserva_gabinete_pj.php";

$link_reserva_crd = $http . "rlt_reserva_crd.php";

$link_memoria_circo = $http . "rlt_memoria_circo.php";

$link_hip_hop_pf = $http . "rlt_hip_hop_pf.php";

$link_hip_hop_pj = $http . "rlt_hip_hop_pj.php";

$link_reserva_padrao = $http."rlt_reserva_padrao.php";

$idPedido = $_POST['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);

if ($pedido['pessoa_tipo_id'] == 1) {
    $link_pc = $linkpf_pc;
    $link_edital = $linkpf_edital;
    $link_reversao = $link_reversao_pf;
    $link_proposta_padrao = $link_proposta_padrao_pf;
    $link_convenio = $link_convenio_pf;
    $link_exclusividade = $link_exclusividade_pf;
    $link_condicionamento = $link_condicionamento_pf;
    $link_facc = $link_facc_pf;
    $link_parecer = $link_parecer_pf;
    $idPessoa = $pedido['pessoa_fisica_id'];
    $link_normas = $link_normas_pf;
} else if ($pedido['pessoa_tipo_id'] == 2) {
    $link_pc = $linkpj_pc;
    $link_edital = $linkpj_edital;
    $link_reversao = $link_reversao_pj;
    $link_proposta_padrao = $link_proposta_padrao_pj;
    $link_convenio = $link_convenio_pj;
    $link_exclusividade = $link_exclusividade_pj;
    $link_condicionamento = $link_condicionamento_pj;
    $link_facc = $link_facc_pj;
    $link_parecer = $link_parecer_pj;
    $idPessoa = $pedido['pessoa_juridica_id'];
    $link_normas = $link_normas_pj;
}

?>
<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header"> Área de Impressão </h3>

        <div class="row">
            <div class="col-md">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">PEDIDO</h3>
                    </div>
                    <div class="box-body">
                        <form action="<?= $link_pc ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-primary center-block">Pedido de Contratação</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">PROPOSTA</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-3">
                            <form action="<?= $link_edital . "23" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Editais
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="<?= $link_proposta_padrao . "13" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Contratações gerais - Com cachê
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="<?= $link_reversao . "13" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Contratações gerais - Reversão de Bilheteria
                                </button>
                            </form>
                        </div>
                        <?php
                        if ($pedido['pessoa_tipo_id'] == 1){
                        ?>
                            <div class="col-md-3">
                                <form action="<?= $link_proposta_convenio . "13" ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Proposta Oficinas / Convênio MINC
                                    </button>
                                </form>
                            </div>
                        <?php
                        }
                        if ($pedido['origem_tipo_id'] == 2) { ?>
                            <div class="col-md-3">
                                <form action="<?= $link_proposta_padrao . "20" ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Vocacional
                                    </button>
                                </form>
                                <hr/>
                            </div>

                            <div class="col-md-3">
                                <form action="<?= $link_proposta_padrao . "21" ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        PIÁ
                                    </button>
                                </form>
                                <hr/>
                            </div>
                        <?php }
                        if ($pedido['origem_tipo_id'] == 3) {
                            ?>
                            <div class="col-md-3">
                                <form action="<?= $link_emia ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        EMIA
                                    </button>
                                </form>
                                <hr/>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">DECLARAÇÃO</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-3">
                            <form action="<?= $link_exclusividade ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Exclusividade
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="<?= $link_convenio ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Convênio 500
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="<?= $link_condicionamento ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Condicionamento
                                </button>
                            </form>
                        </div>
                        <?php
                        if ($pedido['pessoa_tipo_id'] == 1) { ?>
                            <div class="col-md-3">
                                <form action="<?= $link_direitos ?>" target="_blank" method="post">
                                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Direitos Conexos
                                    </button>
                                </form>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">OUTROS</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-3">
                            <form action="<?= $link_facc ?>" target="_blank" method="post">
                                <input type="hidden" name="idPessoa" value="<?= $idPessoa ?>">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    FACC
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="<?= $link_parecer ?>" type="submit" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Parecer da Comissão
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="<?= $link_normas ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    NORMAS INTERNAS - Teatros Municipais
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">PEDIDO DE RESERVA</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-3">
                            <form action="<?= $link_reserva_padrao ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Reserva Padrão
                                </button>
                            </form>
                        </div>

                        <div class="col-md-3">
                            <form action="<?= $link_reserva_global ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Reserva Global
                                </button>
                            </form>
                        </div>

                        <div class="col-md-3">
                            <form action="
                                <?php
                                if ($pedido['pessoa_tipo_id'] == 1) {
                                    echo $link_casa_cultura_pf;
                                } else {
                                    echo $link_casa_cultura_pj;
                                }
                                ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Reserva Casas de Cultura
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>




        <div class="box">
            <div class="box-header">
                <h4 align="center">
                    Qual modelo de documento deseja imprimir?
                </h4>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-static-top bg-light-blue-active">
                        <h4 align="center">PEDIDO DE RESERVA</h4>
                    </nav>
                </div>

                <?php
                if ($pedido['pessoa_tipo_id'] == 1) { ?>
                    <div class="col-md-6">

                        <form action="<?= $link_vai_pf ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Programa VAI
                            </button>
                        </form>
                        <hr/>
                    </div>


                    <div class="col-md-6">
                        <form action="<?= $link_centros_culturais_6354_pf ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Centros Culturais 6354
                            </button>
                        </form>
                        <hr/>
                    </div>


                    <form action="<?= $link_reserva_csmb_pf ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CSMB
                        </button>
                    </form>
                    <hr/>


                    <form action="<?= $link_reserva_ccsp_6393_pf ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CCSP 6393
                        </button>
                    </form>
                    <hr/>


                    <form action="<?= $link_reserva_oficina_pf ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Oficina
                        </button>
                    </form>
                    <hr/>

                    <form action="<?= $link_reserva_gabinete_pf ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Gabinete
                        </button>
                    </form>
                    <hr/>

                    <form action="<?= $link_reserva_dph_pf ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva DPH
                        </button>
                    </form>
                    <hr/>

                    <div class="col-md-12">
                        <form action="<?= $link_hip_hop_pf ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block btn-block" style="width:35%">
                                Mês do Hip-Hop
                            </button>
                        </form>
                        <hr/>
                    </div>
                    <?php
                } else if ($pedido['pessoa_tipo_id'] == 2) { ?>
                    <div class="col-md-6">
                        <form action="<?= $link_centros_culturais_6354_pj ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Centros Culturais e Teatros 6354
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_reserva_csmb_pj ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva CSMB
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_fepac ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                FEPAC
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_reserva_crd ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva CRD
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_vai_pj ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Programa VAI
                            </button>
                        </form>
                        <hr/>
                    </div>


                    <div class="col-md-6">
                        <form action="<?= $link_reserva_gabinete_pj ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Gabinete
                            </button>
                        </form>
                        <hr/>
                    </div>


                    <form action="<?= $link_reserva_dph_pj ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva DPH
                        </button>
                    </form>
                    <hr/>


                    <form action="<?= $link_casa_cultura_pj ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Casas de Cultura
                        </button>
                    </form>
                    <hr/>

                    <form action="<?= $link_reserva_ccsp_6393_pj ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CCSP 6393
                        </button>
                    </form>
                    <hr/>


                    <form action="<?= $link_reserva_oficina_pj ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Oficina
                        </button>
                    </form>
                    <hr/>

                    <form action="<?= $link_memoria_circo ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Centro de Memória do Circo
                        </button>
                    </form>
                    <hr/>

                    <form action="<?= $link_reserva_global_pj ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Global
                        </button>
                    </form>
                    <hr/>

                    <form action="<?= $link_hip_hop_pj ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Mês do Hip-Hop
                        </button>
                    </form>
                    <hr/>
                <?php } ?>

            </div>
        </div>
    </section>
</div>

