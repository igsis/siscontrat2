<?php
$con = bancoMysqli();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";

$linkpf_pc = $http . "impressao_pedido_pf.php";

$linkpj_pc = $http . "impressao_pedido_pj.php";

$linkpf_edital = $http . "exporta_proposta_edital_pf_word.php";

$linkpj_edital = $http . "exporta_proposta_edital_pj_word.php";

$link_emia = $http . "rlt_proposta_emia.php";

$link_proposta_padrao_pf = $http . "rlt_proposta_padrao_pf.php";

$link_proposta_padrao_pj = $http . "rlt_proposta_padrao_pj.php";

$link_reserva_vocacional = $http . "impressao_reserva_vocacional.php";

$link_reserva_sme = $http . "impressao_reserva_sme.php";

$link_reserva_pia = $http . "impressao_reserva_pia.php";

$link_proposta_convenio = $http . "rlt_proposta_oficina_convenio.php";

$link_reversao_pf = $http . "rlt_reversao_proposta_pf.php";

$link_reversao_pj = $http . "rlt_reversao_proposta_pj.php";

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
    $idPessoa = $pedido['pessoa_fisica_id'];
} else if ($pedido['pessoa_tipo_id'] == 2) {
    $link_pc = $linkpj_pc;
    $link_edital = $linkpj_edital;
    $link_reversao = $link_reversao_pj;
    $link_proposta_padrao = $link_proposta_padrao_pj;
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
                            <form action="<?= $link_edital ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Editais
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_reversao ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Contratações gerais - Reversão de Bilheteria
                                </button>
                            </form>
                            <hr/>
                        </div>

                        <div class="col-md-6">
                            <form action="<?= $link_proposta_padrao ?>" target="_blank" method="post">
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
                            <form action="<?= $link_proposta_padrao ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Vocacional
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_edital ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Editais
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_proposta_padrao ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Contratações gerais - Com cachê
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_proposta_convenio ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    Proposta Oficinas / Convênio MINC
                                </button>
                            </form>
                            <hr/>
                        </div>


                        <div class="col-md-6">
                            <form action="<?= $link_proposta_padrao ?>" target="_blank" method="post">
                                <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                                <button type="submit" class="btn btn-outline-info center-block">
                                    PIÁ
                                </button>
                            </form>
                            <hr/>

                            <form action="<?= $link_reversao ?>" target="_blank" method="post">
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
                        <form action="<?= $link_facc . "?id=" . $idPessoa ?>" target="_blank" method="post">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <button type="submit" class="btn btn-outline-info center-block">
                                FACC
                            </button>
                        </form>
                        <hr/>
                    </div>

                    <div class="col-md-6">
                        <form action="#" type="submit" target="_blank" method="post">
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
                        <form action="#" target="_blank" method="post">
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
                    if($pedido['pessoa_tipo_id'] == 1){ ?>
                <div class="col-md-6">
                <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Centros Culturais e Teatros 6391
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Casas de Cultura
                        </button>
                    </form>
                    <hr/>
                    
                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva BMA
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Oficinas CSMB e BMA
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Global
                        </button>
                    </form>
                    <hr/>
                    
                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Programa VAI
                        </button>
                    </form>
                    <hr/>
                </div>

                
                <div class="col-md-6">
                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Centros Culturais 6354
                        </button>
                    </form>
                    <hr/>
                </div>

                
                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CSMB
                        </button>
                    </form>
                    <hr/>
                

                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CCSP 6393
                        </button>
                    </form>
                    <hr/>
          

                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Oficina
                        </button>
                    </form>
                    <hr/>

                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Gabinete
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva DPH
                        </button>
                    </form>
                    <hr/>
                        <?php
                        }else if($pedido['pessoa_tipo_id'] == 2){?>
            <div class="col-md-6">
            <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Centros Culturais e Teatros 6354
                        </button>
                    </form>
                    <hr/>

                <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Centros Culturais e Teatros 6391
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CSMB
                        </button>
                    </form>
                    <hr/>
                    
                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            FEPAC
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva BMA
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Oficinas CSMB e BMA
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CRD
                        </button>
                    </form>
                    <hr/>
                    
                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Programa VAI
                        </button>
                    </form>
                    <hr/>
                </div>

                
                <div class="col-md-6">
                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Gabinete
                        </button>
                    </form>
                    <hr/>
                </div>

                
                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva DPH
                        </button>
                    </form>
                    <hr/>
                

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Casas de Cultura
                        </button>
                    </form>
                    <hr/>

                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva CCSP 6393
                        </button>
                    </form>
                    <hr/>
          

                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Oficina
                        </button>
                    </form>
                    <hr/>

                        <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Centro de Memória do Circo
                        </button>
                    </form>
                    <hr/>

                    <form action="#" target="_blank" method="post">
                        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                        <button type="submit" class="btn btn-outline-info center-block">
                            Reserva Global
                        </button>
                    </form>
                    <hr/>
                        <?php } ?>
                        
            </div>
            <div class="box-footer">
                <form action="?perfil=contrato&p=filtrar_contratos&sp=resumo" method="post">
                    <input type="hidden" name="idEvento" id="idEvento" value="<?= $idEvento ?>">
                    <button type="submit" class="btn btn-default">Voltar</button>
                </form>
            </div>
        </div>
    </section>
</div>

