<?php
$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $idModulo= $_POST['idModulo'];
    $stmt = $conn->prepare("DELETE FROM `modulos` WHERE id = :id");
    $stmt->execute(['id' => $idModulo]);
    $mensagem = mensagem("success", "Módulo excluído com sucesso!");
}

$sql = "SELECT * FROM modulos";
$query = mysqli_query($con, $sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Módulos</h3>
        <a href="?perfil=administrativo&p=modulos&sp=cadastro_modulo" class="text-right btn btn-success"
           style="float: right">Adicionar Módulo</a>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblModulos" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="95%">Módulo</th>
                                <th>Visualizar</th>
                                <th>Excluir</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($modulo = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $modulo['descricao'] . "</td>";
                                echo "<td>
                                    <form method='POST' action='?perfil=administrativo&p=modulos&sp=edita_modulos' role='form'>
                                    <input type='hidden' name='idModulo' value='" . $modulo['id'] . "'>
                                    <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                ?>
                                <td>
                                    <form method='POST' id='formExcliuir'>
                                        <input type="hidden" name='idModulo' value="<?= $modulo['id'] ?>">
                                        <button type="button" class="btn btn-block btn-danger" id="excluiModulo"
                                                data-toggle="modal" data-target="#exclusao" name="excluirModulo"
                                                data-nome="<?= $modulo['descricao'] ?>"
                                                data-id="<?= $modulo['id'] ?>"><span
                                                    class='glyphicon glyphicon-trash'></span></button>
                                    </form>
                                </td>
                                <?php
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Módulo</th>
                                <th>Visualizar</th>
                                <th>Excluir</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

        <!--.modal-->
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmação de Exclusão</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este usuário?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=administrativo&p=modulos&sp=modulos_lista" method="post">
                            <input type="hidden" name="idModulo" id="idModulo" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblModulos').DataTable({
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

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o módulo: ${nome} ?`);
        $(this).find('#idModulo').attr('value', `${id}`);
    })
</script>