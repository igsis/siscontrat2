<?php
$con = bancoMysqli();

if (isset($_POST['excluir'])) {
    $idCargo = $_POST['idCargo'];

    $sql = "UPDATE formacao_cargos SET publicado = 0 WHERE id = '$idCargo'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Cargo excluido com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao excluir o cargo. Tente novamente!");
    }
}

$sql = "SELECT * FROM formacao_cargos WHERE publicado = 1";
$query = mysqli_query($con, $sql);
$num_arrow = mysqli_num_rows($query);

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Cargo</h3>
        <a href="?perfil=formacao&p=administrativo&sp=cargo&spp=cadastro" class="text-right btn btn-success"
           style="float: right">Adicionar Cargo</a>
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
                                <th>Cargo</th>
                                <th>Justificativa</th>
                                <th width="5%">Editar</th>
                                <th width="5%">Excluir</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_arrow == 0) {
                                ?>
                                <tr>
                                    <th colspan="4"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($cargo = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $cargo['cargo'] ?></td>
                                        <td><?= $cargo['justificativa'] ?></td>
                                        <td>
                                            <form action="?perfil=formacao&p=administrativo&sp=cargo&spp=edita"
                                                  method="POST">
                                                <input type="hidden" name="idCargo" id="idCargo"
                                                       value="<?= $cargo['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block">
                                                    <i class='fa fa-file-text-o'></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-block" id="excluiCargo"
                                                    data-toggle="modal" data-target="#exclusao" name="excluiCargo"
                                                    data-id="<?= $cargo['id'] ?>"><span
                                                        class='glyphicon glyphicon-trash'></span></button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Cargo</th>
                                <th>Justificativa</th>
                                <th width="5%">Editar</th>
                                <th width="5%">Excluir</th>
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

