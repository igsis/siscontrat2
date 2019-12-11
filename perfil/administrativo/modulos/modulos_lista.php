<?php
$con = bancoMysqli();
$conn = bancoPDO();

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
                                <th>Editar</th>
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
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Módulo</th>
                                <th>Editar</th>
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