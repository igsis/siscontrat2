<?php
include "includes/menu_principal.php";

$con = bancoMysqli();

$idUser = $_SESSION['idUser'];
$sql = "SELECT ev.id AS idEvento, ev.nome_evento, te.tipo_evento, es.status FROM eventos AS ev
        INNER JOIN tipo_eventos AS te on ev.tipo_evento_id = te.id
        INNER JOIN evento_status es on ev.evento_status_id = es.id
        WHERE publicado = 1 AND (usuario_id = '$idUser' OR fiscal_id = '$idUser' OR suplente_id = '$idUser') AND evento_status_id = 1";
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
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome do evento</th>
                                <th>Tipo do evento</th>
                                <th>Status</th>
                                <th width="10%"></th>
                                <th width="10%"></th>
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
                                    <form method=\"POST\" action=\"?perfil=evento&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='".$evento['idEvento']."'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\">Carregar</button>
                                    </form>
                                </td>";
                                echo "<td>
                    <button type=\"button\" class=\"btn btn-block btn-danger\">Apagar</button>
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
                                <th width="10%"></th>
                                <th width="10%"></th>
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