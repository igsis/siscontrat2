<?php
include "includes/menu_principal.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $evento = $_POST['idEvent'];
    $stmt = $conn->prepare("UPDATE eventos SET publicado = 0 WHERE id = :id");
    $stmt->execute(['id' => $evento]);
    $mensagem = mensagem("success", "Evento excluido com sucesso!");

}

$idUser = $_SESSION['idUser'];
$sql = "SELECT ev.id AS idEvento, ev.nome_evento, te.tipo_evento, es.status, ev.usuario_id FROM eventos AS ev
        INNER JOIN tipo_eventos AS te on ev.tipo_evento_id = te.id
        INNER JOIN evento_status es on ev.evento_status_id = es.id
        WHERE publicado = 1 AND (usuario_id = '$idUser' OR fiscal_id = '$idUser' OR suplente_id = '$idUser') AND evento_status_id = 1 OR evento_status_id = 5";
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
                                <th>Nome do evento</th>
                                <th>Tipo do evento</th>
                                <th>Status</th>
                                <th>Visualizar</th>
                                <th>Apagar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $evento['nome_evento'] . "</td>";
                                echo "<td>" . $evento['tipo_evento'] . "</td>";
                                if ($evento['status'] == "Cancelado") {
                                    $idEvento = $evento['idEvento'];
                                    $nomeEvento = $evento['nome_evento'];
                                    $sqlChamado = "SELECT c.justificativa, c.data FROM chamados AS c WHERE evento_id = $idEvento";
                                    $chamado = $con->query($sqlChamado)->fetch_array();

                                    //operador
                                    $testa = $con->query("SELECT operador_id FROM pedidos WHERE origem_id = $idEvento")->fetch_array();
                                    $idUsuario = $eventos['operador_id'];
                                    if ($idUsuario != 0) {
                                        $operadorAux = "AND usuario_id = $idUsuario";
                                        $sqlOperador = "SELECT u.nome_completo FROM usuarios AS u INNER JOIN usuario_contratos uc ON u.id = uc.usuario_id WHERE u.id = $idUsuario $operadorAux";
                                        $operador = $con->query($sqlOperador)->fetch_array();
                                    }
                                    ?>
                                    <td>
                                        <button type="button" class="btn-link" id="exibirMotivo"
                                                data-toggle="modal" data-target="#exibicao" name="exibirMotivo">
                                            <p class="text-danger"><?= $evento['status'] ?></p>
                                        </button>
                                    </td>
                                <?php } else {
                                    echo "<td>" . $evento['status'] . "</td>";
                                }
                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='" . $evento['idEvento'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                    </form>
                                </td>";
                                ?>
                                <td>
                                    <form method="post" id="formExcluir">
                                        <input type="hidden" name="idEvento" value="<?= $evento['idEvento'] ?>">
                                        <button type="button" class="btn btn-block btn-danger" id="excluiEvento"
                                                data-toggle="modal" data-target="#exclusao" name="excluiEvento"
                                                data-name="<?= $evento['nome_evento'] ?>"
                                                data-id="<?= $evento['idEvento'] ?>"><span
                                                    class="glyphicon glyphicon-trash"></span></button>
                                    </form>
                                </td>
                                <?php
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

        <!--.modal-->
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
                        <form action="?perfil=evento&p=evento_lista" method="post">
                            <input type="hidden" name="idEvent" id="idEvent" value="">
                            <input type="hidden" name="apagar" id="apagar">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                            </button>
                            <input class=" btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="exibicao" class="modal modal fade in" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Motivo do Cancelamento</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nome do Evento:</strong> <?=$nomeEvento?></p>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Motivo</th>
                                <th width="20%">Operador de Contratos</th>
                                <th width="15%">Data</th>
                            </tr>
                            </thead>
                            <tbody>
                                <td><?=$chamado['justificativa']?></td>
                                <?php
                                    if(isset($operador['nome_completo'])){
                                        echo "<td>" . $operador['nome_completo']. "</td>";
                                    }else{
                                        echo "<td> </td>";
                                    }
                                ?>

                                <td><?=exibirDataBr($chamado['data'])?></td>
                            </tbody>
                        </table>
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
<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let evento = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o evento ${evento} ?`);
        $(this).find('#idEvent').attr('value', `${id}`);
    })
</script>