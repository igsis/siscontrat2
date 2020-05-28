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

$link_centroculturaisteatro_pf_6391 = $http . "rlt_pedido_reserva_centrosculturaisteatros_pf_6391.php";

$link_centroculturaisteatro_pj_6391 = $http . "rlt_pedido_reserva_centrosculturaisteatros_pj_6391.php";

$link_casa_cultura_pf = $http . "rlt_casa_cultura_pf.php";

$link_casa_cultura_pj = $http . "rlt_casa_cultura_pj.php";

$link_reserva_bma_pf = $http . "rlt_reserva_bma_pf.php";

$link_reserva_bma_pj = $http . "rlt_reserva_bma_pj.php";

$link_bma_csmb_pf = $http . "rlt_reserva_bma_csmb_pf.php";

$link_bma_csmb_pj = $http . "rlt_reserva_bma_csmb_pj.php";

$link_vai_pf = $http . "rlt_programa_vai_pf.php";

$link_vai_pj = $http . "rlt_programa_vai_pj.php";

$link_fepac = $http . "rlt_fepac.php";

$link_reserva_global_pf = $http . "rlt_reserva_global_pf.php";

$link_reserva_global_pj = $http . "rlt_reserva_global_pj.php";

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

$idPedido = $_POST['idPedido'];

$idEvento = $_SESSION['idEvento'];

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
                        <div class="col-md-12">
                            <form action="<?= $link_pc ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-primary center-block">
                                    Pedido de Contratação
                                </button>
                            </form>
                            <hr/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">PROPOSTA</h4>
                        </nav>
                    </div>
                    <?php
                    if ($pedido['pessoa_tipo_id'] == 2) { ?>
                        <div class="col-md-6">
                            <form action="<?= $link_edital . "23" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Editais
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_reversao . "13" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Contratações gerais - Reversão de Bilheteria
                                </button>
                            </form>
                            <hr/>
                        </div>

                        <div class="col-md-6">
                            <form action="<?= $link_proposta_padrao . "13" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Contratações gerais - Com cachê
                                </button>
                            </form>
                            <hr/>
                        </div>
                    <?php } else if ($pedido['pessoa_tipo_id'] == 1) {
                        ?>
                        <div class="col-md-6">
                            <form action="<?= $link_proposta_padrao . "20" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Vocacional
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_edital . "23" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Editais
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_proposta_padrao . "13" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Contratações gerais - Com cachê
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_proposta_convenio . "13" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Proposta Oficinas / Convênio MINC
                                </button>
                            </form>
                            <hr/>
                        </div>


                        <div class="col-md-6">
                            <form action="<?= $link_proposta_padrao . "21" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    PIÁ
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_reversao . "13" ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Contratações gerais - Reversão de Bilheteria
                                </button>
                            </form>
                            <hr/>
                        </div>

                    <?php }
                    if ($pedido['origem_tipo_id'] == 3) {
                        ?>
                        <form action="<?= $link_emia ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                EMIA
                            </button>
                        </form>
                        <hr/>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">DECLARAÇÃO</h4>
                        </nav>
                    </div>
                    <?php
                    if ($pedido['pessoa_tipo_id'] == 1) { ?>
                        <div class="col-md-6">
                            <form action="<?= $link_direitos ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Direitos Conexos
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_convenio ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Convênio 500
                                </button>
                            </form>
                            <hr/>
                        </div>
                        <div class="col-md-6">
                            <form action="<?= $link_exclusividade ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Exclusividade
                                </button>
                            </form>
                            <hr/>

                        </div>
                    <?php } else if ($pedido['pessoa_tipo_id'] == 2) { ?>
                        <div class="col-md-12">
                            <form action="<?= $link_exclusividade ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Exclusividade
                                </button>
                            </form>
                            <hr/>
                        </div>
                        <div class="col-md-6">
                            <form action="<?= $link_convenio ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Convênio 500
                                </button>
                            </form>
                            <hr/>
                        </div>
                    <?php } ?>
                    <form action="<?= $link_condicionamento ?>" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Condicionamento
                        </button>
                    </form>
                    <hr/>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-static-top bg-light-blue-active">
                            <h4 align="center">OUTROS</h4>
                        </nav>
                    </div>

                    <div class="col-md-6">
                        <form action="<?= $link_facc ?>" target="_blank" method="post">
                            <input type="hidden" name="idPf" value="<?= $idPessoa ?>">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                FACC
                            </button>
                        </form>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <form action="<?= $link_parecer ?>" type="submit" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Parecer da Comissão
                            </button>
                        </form>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <form action="<?= $link_normas ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
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

                <?php
                if ($pedido['pessoa_tipo_id'] == 1) { ?>
                    <div class="col-md-6">
                        <form action="<?= $link_centroculturaisteatro_pf_6391 ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Centros Culturais e Teatros 6391
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_casa_cultura_pf ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Casas de Cultura
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_reserva_bma_pf ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva BMA
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_bma_csmb_pf ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Oficinas CSMB e BMA
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_reserva_global_pf ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Global
                            </button>
                        </form>
                        <hr/>

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

                        <form action="<?= $link_centroculturaisteatro_pj_6391 ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Centros Culturais e Teatros 6391
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

                        <form action="<?= $link_reserva_bma_pj ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva BMA
                            </button>
                        </form>
                        <hr/>

                        <form action="<?= $link_bma_csmb_pj ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                Reserva Oficinas CSMB e BMA
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
                <?php } ?>

            </div>
        </div>
    </section>
</div>

