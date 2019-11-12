<?php
$con = bancoMysqli();

$sql = "SELECT p.id, p.origem_id,fc.protocolo, fc.ano, p.numero_processo,fc.num_processo_pagto, pf.nome, v.verba, fs.status, fc.form_status_id 
            FROM pedidos p 
            INNER JOIN formacao_contratacoes fc ON fc.id = p.origem_id 
            INNER JOIN pessoa_fisicas pf ON fc.pessoa_fisica_id = pf.id
            INNER JOIN verbas v on p.verba_id = v.id 
            INNER JOIN formacao_status fs on fc.form_status_id = fs.id
            WHERE fc.form_status_id != 5 AND p.publicado = 1 AND fc.publicado = 1 AND p.origem_tipo_id = 2";

$query = mysqli_query($con, $sql);
$num_arrow = mysqli_num_rows($query);

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Pedido de contratação</h3>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblCargo" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Processo</th>
                                <th>Proponente</th>
                                <th>Local</th>
                                <th>Ano</th>
                                <th>Verba</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_arrow == 0) {
                                ?>
                                <tr>
                                    <th colspan="7"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($row = mysqli_fetch_array($query)) {
                                    $idFc = $row['origem_id'];
                                    $sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFc'";
                                    $local = "";
                                    $queryLocal = mysqli_query($con, $sqlLocal);

                                    if ($row['form_status_id'] == '2' || $row['form_status_id'] == '4')

                                        $cor = 'rgba(234, 0, 0, 0.5)';
                                    else
                                        $cor = 'rgba(0, 214, 21, 0.5)';
                                    ?>
                                    <tr style="background: <?= $cor ?>">
                                        <td>
                                            <form action="?perfil=formacao&p=pedido_contratacao&sp=edita" method="POST">
                                                <input type="hidden" name="idPedido" id="idPedido"
                                                       value="<?= $row['id'] ?>">
                                                <button type="submit" name="carregar"
                                                        class="btn btn-primary btn-block"><?= $row['protocolo'] ?></button>
                                            </form>
                                        </td>

                                        <td><?= $row['numero_processo'] ?></td>
                                        <td><?= $row['nome'] ?></td>
                                        <td>
                                            <?php
                                            while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                                $local = $local . $linhaLocal['local'] . ' - ';
                                            }

                                            $local = substr($local, 0, -3);
                                            echo $local;
                                            ?>
                                        </td>
                                        <td><?= $row['ano'] ?></td>
                                        <td><?= $row['verba'] ?></td>
                                        <td><?= $row['status'] ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Processo</th>
                                <th>Proponente</th>
                                <th>Local</th>
                                <th>Ano</th>
                                <th>Verba</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL -->
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este cargo?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=formacao&p=administrativo&sp=cargo&spp=index" method="post">
                            <input type="hidden" name="idCargo" id="idCargo" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Excluir">
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>
</div>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idCargo').attr('value', `${id}`);
    })
</script>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblCargo').DataTable({
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

