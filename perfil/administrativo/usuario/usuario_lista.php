<?php
$con = bancoMysqli();

$sql = "SELECT * FROM usuarios";
$query = mysqli_query($con,$sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista de Usuário</h3>
        <a href="?perfil=administrativo&p=usuario&sp=cadastro_usuario" class="text-right btn btn-success" style="float: right">Adicionar Usuario</a>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblUsuario" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>RF/RG</th>
                                <th>email</th>
                                <th>telefone</th>
                                <th>Visualizar</th>
                                <th>Excluir</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($usuario = mysqli_fetch_array($query)){
                                echo "<tr>";
                                echo "<td>".$usuario['nome_completo']."</td>";
                                echo "<td>".$usuario['rf_rg']."</td>";
                                echo "<td>".$usuario['email']."</td>";
                                echo "<td>".$usuario['telefone']."</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=administrativo&p=usuario&sp=edita_usuario\" role=\"form\">
                                    <input type='hidden' name='idUsuario' value='".$usuario['id']."'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                echo "<td>
                    <button type=\"button\" class=\"btn btn-block btn-danger\"><span class='glyphicon glyphicon-trash'></span></button>
                  </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>RF/RG</th>
                                <th>email</th>
                                <th>telefone</th>
                                <th colspan="2" width="15%"></th>
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
        $('#tblUsuario').DataTable({
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