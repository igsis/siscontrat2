<?php
include "includes/menu_principal.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

$idUser = $_SESSION['idUser'];
$sql = "SELECT eve.id idEvento, prot.protocolo, cat.categoria_atracao, eve.nome_evento, loc.local, sta.status
                               FROM eventos eve
                               INNER JOIN atracoes atr
                               ON eve.id = atr.evento_id
                               INNER JOIN protocolos prot
                               ON atr.id = prot.origem_id
                               INNER JOIN ocorrencias oco
                               ON atr.id = oco.origem_ocorrencia_id
                               INNER JOIN locais loc
                               ON oco.local_id = loc.id
                               INNER JOIN categoria_atracoes cat
                               ON atr.categoria_atracao_id = cat.id
                               INNER JOIN evento_status sta
                               ON eve.evento_status_id = sta.id
                               WHERE eve.publicado = 1
                               AND ((eve.usuario_id = '$idUser') OR (eve.fiscal_id = '$idUser') OR (eve.suplente_id = '$idUser'))
                               AND eve.evento_status_id >= 3
                               AND evento_interno = 0";

$query = mysqli_query($con, $sql);
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

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblEvento" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Objeto</th>
                                <th>Local</th>
                                <th>Período</th>
                                <th>Status</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $evento['protocolo'] . "</td>";
                                echo "<td>" . $evento['categoria_atracao'] . " - " . $evento['nome_evento'] . "</td>";
                                echo "<td>" . $evento['local'] . "</td>";
                                echo "<td>" . $evento['local'] . "</td>";
                                echo "<td>" . $evento['status'] . "</td>";
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='" . $evento['idEvento'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Objeto</th>
                                <th>Local</th>
                                <th>Período</th>
                                <th>Status</th>
                                <th>Visualizar</th>
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
