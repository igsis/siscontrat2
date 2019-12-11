<?php
$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $categoria = $_POST['idCategoria'];
    $stmt = $conn->prepare("UPDATE `categoria_atracoes` SET publicado = 0 WHERE id = :id");
    $stmt->execute(['id' => $categoria]);
    $mensagem = mensagem("success", "Categoria excluida com sucesso!");
}

$sql = "SELECT * FROM categoria_atracoes WHERE publicado = 1";
$query = mysqli_query($con, $sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Categorias</h3>
        <a href="?perfil=administrativo&p=categoria&sp=cadastro_categoria" class="text-right btn btn-success"
           style="float: right">Adicionar Categoria</a>
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
                        <table id="tblPerfil" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="90%">Categoria atração</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($categoria = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $categoria['categoria_atracao'] . "</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=administrativo&p=categoria&sp=edita_categoria\" role=\"form\">
                                    <input type='hidden' name='idCategoria' value='" . $categoria['id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                ?>
                                <td>
                                    <form method='POST' id='formExcliuir'>
                                        <input type="hidden" name='idCategoria' value="<?= $categoria['id'] ?>">
                                        <button type="button" class="btn btn-block btn-danger" id="excluiPerfil"
                                                data-toggle="modal" data-target="#exclusao" name="excluiPerfil"
                                                data-nome="<?= $categoria['categoria_atracao'] ?>"
                                                data-id="<?= $categoria['id'] ?>"><span
                                                    class='glyphicon glyphicon-trash'></span></button>
                                    </form>
                                </td>
                                <?php
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Categoria atração</th>
                                <th>Editar</th>
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
                        <p>Tem certeza que deseja excluir esta categoria?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="?perfil=administrativo&p=categoria&sp=categoria_lista" method="post">
                            <input type="hidden" name="idCategoria" id="idCategoria" value="">
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
        $('#tblPerfil').DataTable({
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

        $(this).find('p').text(`Tem certeza que deseja excluir a categoria: ${nome} ?`);
        $(this).find('#idCategoria').attr('value', `${id}`);
    })
</script>