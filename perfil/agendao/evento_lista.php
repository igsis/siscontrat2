<?php
unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

if(isset($_POST['excluir'])){
    $evento = $_POST['idEvent'];
    $stmt = $conn->prepare("UPDATE agendoes SET publicado = 0 WHERE id =:id");
    $stmt->execute(['id' => $evento]);
    $mensagem = mensagem("success", "Evento excluido com sucesso!");
}

$idUser = $_SESSION['idUser'];
$sql = "SELECT ev.id AS idEvento, ev.nome_evento FROM agendoes AS ev
        WHERE publicado = 1 AND usuario_id = '$idUser' AND evento_status_id = 1";

$query = mysqli_query($con, $sql);
$linha = mysqli_num_rows($query);

if ($linha >= 1) {
    $tem = 1;
} else {
    $tem = 0;
}
?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <?php
                    if ($tem == 0)  {
                        $mensagemEvento = mensagem("info", "Não existe eventos enviados!");
                        echo $mensagemEvento;
                    } else {

                        ?>
                        <div class="row" align="center">
                            <?php if (isset($mensagem)) {
                                echo $mensagem;
                            }; ?>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="tblEvento2" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nome do evento</th>
                                    <th>Local</th>
                                    <th>Período</th>
                                    <th width="10%">Visualizar</th>
                                    <th width="10%">Apagar</th>
                                </tr>
                                </thead>

                                <?php
                                echo "<tbody>";
                                while ($evento = mysqli_fetch_array($query)) {
                                    $locais = listaLocais($evento['idEvento']);
                                    echo "<tr>";
                                    echo "<td>" . $evento['nome_evento'] . "</td>";
                                    echo "<td>" . $locais . "</td>";
                                    echo "<td>" . retornaPeriodoNovo($evento['idEvento'], 'agendao_ocorrencias') . "</td>";
                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=agendao&p=evento_edita\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='" . $evento['idEvento'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class='fa fa-file-text-o'></i></button>
                                    </form>
                                </td>";
                                    ?>
                                    <td>
                                        <form method="post" id="formExcluir">
                                            <input type="hidden" name="idEvento" value="<?= $evento['idEvento'] ?>">
                                            <button type="button" class="btn btn-block btn-danger" id="excluiEvento"
                                                    data-toggle="modal" data-target="#exclusao" name="excluiEvento"
                                                    data-name="<?= $evento['nome_evento'] ?>"
                                                    data-id="<?= $evento['idEvento'] ?>">
                                                <span class="glyphicon glyphicon-trash"></span></button>
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
                                    <th>Local</th>
                                    <th>Período</th>
                                    <th>Visualizar</th>
                                    <th>Apagar</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <?php
                        }
                        ?>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->
        <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Confirmação de Exclusão</h4>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este evento?</p>
                        <div class="modal-footer">
                            <form action="?perfil=agendao&p=evento_lista" method="post">
                                <input type="hidden" name="idEvent" id="idEvent" value="">
                                <input type="hidden" name="apagar" id="apagar">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblEvento2').DataTable({
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
    $('#exclusao').on('show.bs.modal',function (e){
        let evento = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o evento ${evento}?`);
        $(this).find('#idEvent').attr('value',`${id}`);
    })
</script>