<?php
include "includes/menu_principal.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();

$idUser = $_SESSION['idUser'];
$sql = "SELECT ev.id AS idEvento, ev.nome_evento, te.tipo_evento, es.status FROM eventos AS ev
        INNER JOIN tipo_eventos AS te on ev.tipo_evento_id = te.id
        INNER JOIN evento_status es on ev.evento_status_id = es.id
        WHERE publicado = 1 AND (usuario_id = '$idUser' OR fiscal_id = '$idUser' OR suplente_id = '$idUser') AND evento_status_id = 1 AND evento_interno = 1";
$query = mysqli_query($con,$sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblEvento" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome do evento</th>
                                <th>Tipo do evento</th>
                                <th>Status</th>
                                <th>Visualizar</th>
                                <th>Apagar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)){
                                echo "<tr>";
                                echo "<td>".$evento['nome_evento']."</td>";
                                echo "<td>".$evento['tipo_evento']."</td>";
                                echo "<td>".$evento['status']."</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento_interno&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='".$evento['idEvento']."'>
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
                                <th>Nome do evento</th>
                                <th>Tipo do evento</th>
                                <th>Status</th>
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
        $('#tblEvento').DataTable({
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