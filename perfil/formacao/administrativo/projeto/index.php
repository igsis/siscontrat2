<?php
$con = bancoMysqli();

if (isset($_POST['excluir'])) {
    $idProjeto = $_POST['idProjeto'];

    $sql = "UPDATE projetos SET publicado = 0 WHERE id = '$idProjeto'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Projeto excluido com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao excluir o projeto. Tente novamente!");
    }
}

$sql = "SELECT * FROM projetos WHERE publicado = 1";
$query = mysqli_query($con, $sql);
$num_arrow = mysqli_num_rows($query);
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Projeto</h3>
        <a href="?perfil=formacao&p=administrativo&sp=projeto&spp=cadastro" class="text-right btn btn-success"
           style="float: right">Adicionar Projeto</a>
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
                        <table id="tblProjeto" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Projeto</th>
                                <th width="5%">Editar</th>
                                <th width="5%">Excluir</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_arrow == 0) {
                                ?>
                                <tr>
                                    <th colspan="3"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($projeto = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $projeto['projeto'] ?></td>
                                        <td>
                                            <form action="?perfil=formacao&p=administrativo&sp=projeto&spp=edita"
                                                  method="POST">
                                                <input type="hidden" name="idProjeto" id="idProjeto"
                                                       value="<?= $projeto['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block">
                                                    <i class='fa fa-file-text-o'></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method='POST' id='formExcliuir'>
                                                <input type="hidden" name='idProjeto' id="idProjeto"
                                                       value="<?= $projeto['id'] ?>">
                                                <button type="button" class="btn btn-danger btn-block" id="excluiProjeto"
                                                        data-toggle="modal" data-target="#exclusao" name="excluiProjeto"
                                                        data-id="<?= $projeto['id'] ?>"><span
                                                            class='glyphicon glyphicon-trash'></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Projeto</th>
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
                        <p>Tem certeza que deseja excluir este projeto?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=formacao&p=administrativo&sp=projeto&spp=index" method="post">
                            <input type="hidden" name="idProjeto" id="idProjeto" value="">
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

        $(this).find('#idProjeto').attr('value', `${id}`);
    })
</script>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblProjeto').DataTable({
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

