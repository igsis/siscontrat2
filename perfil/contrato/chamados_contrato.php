<?php
$con = bancoMysqli();
if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}
if (isset($_POST['idPedido'])) {
    $idPedido = $_POST['idPedido'];
}


$sql = "select u.nome_completo, c.justificativa, u.id, c.data, es.status, e.id,e.nome_evento
from chamados as c
inner join usuarios as u on c.usuario_id = u.id
inner join eventos as e on e.id = c.evento_id
inner join evento_status as es on es.id = e.evento_status_id WHERE e.id = $idEvento";

$query = mysqli_query($con, $sql);
$num_rows = mysqli_num_rows($query);

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Resultado de busca</h3>
        <div class="row" align="center">

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Chamado</th>
                                <th>Data do Envio</th>
                                <th>Usuário</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($num_rows == 0) { ?>
                                <tr>
                                    <th colspan="7"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                            <?php } else {
                                while ($dados = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $dados['id'] ?></td>
                                        <td>
                                            <form action="?perfil=contrato&p=chamado_edita" method="post" role="form">
                                                <input type="hidden" name="idEvento" id="idEvento" value="<?=$idEvento?>">
                                                <input type="hidden" name="idPedido" id="idPedido" value="<?=$idPedido?>">
                                                <button type="submit" class="btn btn-link" name="load"><?= $dados['justificativa'] ?> - <?= $dados['nome_evento'] ?></button>
                                            </form>
                                        </td>
                                        <td><?= $dados['data'] ?></td>
                                        <td><?= $dados['nome_completo'] ?></td>
                                        <td><?= $dados['status'] ?></td>
                                    </tr>
                                <?php }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Chamado</th>
                                <th>Data do Envio</th>
                                <th>Usuário</th>
                                <th>Status</th>
                            </tr>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <form action="?perfil=contrato&p=resumo" method="post" role="form">
                            <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                            <button type="submit" name="Voltar" class="btn btn-default pull-left">Voltar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#tblResultado').DataTable({
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