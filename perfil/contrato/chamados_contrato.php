<?php
$con = bancoMysqli();
if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}
if (isset($_POST['idPedido'])) {
    $idPedido = $_POST['idPedido'];
}


$sql = "SELECT c.id, c.justificativa, c.data, u.nome_completo, s.status
        FROM chamados AS c
        INNER JOIN usuarios AS u ON c.usuario_id = u.id
        INNER JOIN eventos AS e ON c.evento_id = e.id
        INNER JOIN evento_status AS s ON s.id = e.evento_status_id
        WHERE e.id = $idEvento";

$query = mysqli_query($con, $sql);

$nomeEvento = $con->query("SELECT nome_evento FROM eventos WHERE id = $idEvento AND tipo_evento_id != 3 AND publicado = 1")->fetch_array()['nome_evento'];
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Chamados do Evento: <?= $nomeEvento ?></h3>
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
                            while ($dados = mysqli_fetch_array($query)) {
                                ?>
                                <tr>
                                    <td><?= $dados['id'] ?></td>
                                    <td>
                                        <form action="?perfil=contrato&p=chamado_edita" method="post" role="form">
                                            <input type="hidden" name="idEvento" id="idEvento" value="<?= $idEvento ?>">
                                            <input type="hidden" name="idPedido" id="idPedido" value="<?= $idPedido ?>">
                                            <input type="hidden" name="idChamado" value="<?= $dados['id'] ?>">
                                            <button type="submit" class="btn btn-link"
                                                    name="load"><?= $dados['justificativa'] ?></button>
                                        </form>
                                    </td>
                                    <td><?= exibirDataBr($dados['data']) ?></td>
                                    <td><?= $dados['nome_completo'] ?></td>
                                    <td><?= $dados['status'] ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Chamado</th>
                                <th>Data do Envio</th>
                                <th>Usuário</th>
                                <th>Status</th>
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