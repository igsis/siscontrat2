<?php
$con = bancoMysqli();

$sql = "SELECT * FROM penalidades";
$query = mysqli_query($con, $sql);

?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Lista de Penalidades</h3>
        <a href="?perfil=contrato&p=admin&sp=penalidades_cadastra">
            <button type="button" class="btn btn-success pull-right">Criar uma nova penalidade</button>
        </a>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="box-body">
                        <table id="tblPenalidade" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Modelo</th>
                                <th width="10%">Editar</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                while ($penal = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $penal['modelo'] ?></td>
                                        <td>
                                            <form action="?perfil=contrato&p=admin&sp=penalidades_edita" method="POST">
                                                <input type="hidden" name="idPenalidade" id="idPenalidade"
                                                       value="<?= $penal['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block"><span class="glyphicon glyphicon-eye-open"></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Modelo</th>
                                <th width="10%">Editar</th>
                            </tr>
                            </tfoot>
                        </table>
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
        $('#tblPenalidade').DataTable({
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

