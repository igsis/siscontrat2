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
        WHERE publicado = 1 AND evento_status_id between 3 AND 6 AND contratacao = 0  
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
                                    </form>";
                          if ($evento['evento_status_id'] == 5 || $evento['evento_status_id'] == 6) {
                                    echo "<button type='button' class='btn btn-block btn-danger'
                                                                 id='excluiEvento'
                                                                 data-toggle='modal' data-target='#exclusao'
                                                                 name='excluiEvento'
                                                                 data-name='{$evento['nome_evento']}'
                                                                 data-id='{$evento['idEvento']}'><span
                                                    class='glyphicon glyphicon-trash'></span></button>";
                                }
                                echo"</td>";
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

<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este evento?</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="idEvento" id="idEvent" value="">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                    </button>
                    <input class=" btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                </form>
            </div>
        </div>
    </div>
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

    $('#exclusao').on('show.bs.modal', function (e) {
        let evento = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o evento ${evento} ?`);
        $(this).find('#idEvent').attr('value', `${id}`);
    })
</script>