<?php
include "includes/menu_principal.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

$idUser = $_SESSION['usuario_id_s'];
$sql = "SELECT eve.id AS idEvento, eve.protocolo, eve.nome_evento, es.status
        FROM eventos as eve
        INNER JOIN evento_status es on eve.evento_status_id = es.id
        WHERE publicado = 1 AND evento_status_id between 3 AND 4 AND contratacao = 0  
        AND (suplente_id = '$idUser' OR fiscal_id = '$idUser' OR usuario_id = '$idUser')";
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
                                <th>Chamados</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)) {
                                $idEvento = $evento['idEvento'];
                                echo "<tr>";
                                echo "<td>" . $evento['protocolo'] . "</td>";
                                echo "<td>" . $evento['nome_evento'] . "</td>";
                                ?>
                                <td>
                                    <button type="button" class="btn btn-primary btn-block" id="exibirLocais"
                                            data-toggle="modal" data-target="#modalLocais_Inst" data-name="local"
                                            onClick="exibirLocal_Instituicao('<?= 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_listar_locais_instituicoes.php' ?>', '#modalLocais_Inst', '#modalTitulo')"
                                            data-id="<?= $idEvento ?>"
                                            name="exibirLocais">
                                        Ver locais
                                    </button>
                                </td>
                            <?php
                                echo "<td>" . retornaPeriodoNovo($idEvento, 'ocorrencias') . "</td>";
                                echo "<td>" . $evento['status'] . "</td>";
                                print_r(retornaChamadosTD($evento['idEvento']));
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=resumo_evento_enviado\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='" . $idEvento . "'>
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
                                <th>Chamados</th>
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