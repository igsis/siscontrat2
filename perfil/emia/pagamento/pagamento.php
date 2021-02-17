<?php
$con = bancoMysqli();
$idPedido = $_POST['idPedido'];

$sqlPedido = "SELECT * FROM pedidos WHERE origem_tipo_id = 3 AND id = '{$idPedido}'";
$pedido = $con->query($sqlPedido)->fetch_array();
$ec = recuperaDados('emia_contratacao', 'id', $pedido['origem_id']);
$pf = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);

$sql = "SELECT em.* FROM emia_parcelas AS em
        INNER JOIN emia_contratacao AS ec
        ON em.emia_vigencia_id = ec.emia_vigencia_id
        INNER JOIN pedidos AS p 
        ON ec.id = p.origem_id
        WHERE p.id = '{$idPedido}'";
$query = mysqli_query($con, $sql);

$idLocal = $ec['local_id'];
$sqlLocal = "SELECT local FROM locais WHERE id = $idLocal";
$local = $con->query($sqlLocal)->fetch_array();

$idPf = $pf['id'];

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";

$link_facc = $http . "rlt_fac_pf.php";

$link_pagamento = $http . "rlt_pagamento_emia.php";

$link_recibo = $http . "rlt_recibo_emia.php";

$link_atestado = $http . "rlt_atestado_servico_emia.php";

$link_horas = $http . "rlt_horas_emia.php";

$link_contabilidade = $http . "rlt_contabilidade_emia.php";
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Pedido de pagamento da EMIA</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form method="POST" action="?perfil=formacao&p=pagamento&sp=pagamento"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="protocolo">Protocolo:</label>
                                    <input type="text" name="protocolo" class="form-control" value="<?= $ec['protocolo'] ?>"
                                           disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="numProcesso">Número do Processo:</label>
                                    <input type="text" name="numProcesso" class="form-control" value="<?= $pedido['numero_processo'] ?>"
                                           disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local:</label>
                                    <input type="text" name="local" class="form-control" value="<?= $local['local'] ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </form>

                        <div class="box-footer">
                            <form action="<?= $link_facc ?>" method="POST" target="_blank">
                                <input type="hidden" name="idPf" value="<?=$idPf?>">
                                <button type="submit" class="btn btn-primary center-block">Gerar FACC</button>
                            </form>
                        </div>


                    <table id="tblParcela" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Parcela</th>
                            <th>Valor</th>
                            <th>Pagamento</th>
                            <th></th>
                            <th></th>
                            <th style="text-align:center">Gerar</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                            while ($parcela = mysqli_fetch_array($query)) {
                                ?>
                                <tr>
                                    <td><?= $parcela['numero_parcelas'] ?></td>
                                    <td><?= dinheiroParaBr($parcela['valor']) ?></td>
                                    <td><?= exibirDataBr($parcela['data_pagamento']) ?></td>

                                    <th style="text-align:center">
                                        <form action="<?= $link_pagamento ?>" method="post" target="_blank">
                                            <input type="hidden" value="<?= $parcela['id'] ?>" name="idParcela">
                                            <input type="hidden" value="<?=$idPedido?>" name="idPedido">
                                            <button type="submit" class="btn btn-primary">Pagamento</button>
                                        </form>
                                    </th>

                                    <th style="text-align:center">
                                        <form action="<?= $link_recibo ?>" method="post" target="_blank">
                                            <input type="hidden" value="<?= $parcela['id'] ?>" name="idParcela">
                                            <input type="hidden" value="<?=$idPedido?>" name="idPedido">
                                            <button type="submit" class="btn btn-primary">Recibo</button>
                                        </form>
                                    </th>

                                    <th style="text-align:center">
                                        <form action="<?= $link_atestado ?>" method="post" target="_blank">
                                            <input type="hidden" value="<?= $parcela['id'] ?>" name="idParcela">
                                            <input type="hidden" value="<?=$idPedido?>" name="idPedido">
                                            <button type="submit" class="btn btn-primary">Atestado Serviço</button>
                                        </form>
                                    </th>

                                    <th style="text-align:center">
                                        <form action="<?= $link_horas ?>" method="post" target="_blank">
                                            <input type="hidden" value="<?= $parcela['id'] ?>" name="idParcela">
                                            <input type="hidden" value="<?=$idPedido?>" name="idPedido">
                                            <button type="submit" class="btn btn-primary">FFI</button>
                                        </form>
                                    </th>

                                </tr>
                                <?php
                        }
                        ?>
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Parcela</th>
                            <th>Valor</th>
                            <th>Pagamento</th>
                            <th></th>
                            <th></th>
                            <th style="text-align:center">Gerar</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblParcela').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
</script>
