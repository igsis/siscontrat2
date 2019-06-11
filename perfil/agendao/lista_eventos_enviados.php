<?php

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

$idUser = $_SESSION['idUser'];
$sql = "SELECT * FROM eventos WHERE publicado = 1 AND agendao = 1 AND contratacao = 0 AND evento_status_id >= 3 AND (suplente_id = '$idUser' OR fiscal_id = '$idUser' OR usuario_id = '$idUser')";
$query = mysqli_query($con, $sql);
$linha = mysqli_num_rows($query);
if ($linha >= 1) {
    $tem = 1;
} else {
    $tem = 0;
}
$num_atracoes = 0;

?>

<!-- START FORM-->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>

                    <?php
                    if ($tem == 0) {
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
                                    $idEvento = $evento['id'];
                                    $locais = listaLocais($idEvento);

                                    $protocolos = recuperaDados("protocolos", "origem_id", $idEvento);
                                    $status = recuperaDados("evento_status", "id", $evento['evento_status_id']);

                                    $sql_atracoes = "SELECT * FROM atracoes WHERE evento_id = '$idEvento'";
                                    $query_atracoes = mysqli_query($con, $sql_atracoes);
                                    $num_atracoes = mysqli_num_rows($query_atracoes);

                                    echo "<tr>";
                                    echo "<td>" . $protocolos['protocolo'] . "</td>";
                                    echo "<td>";
                                    while ($atracao = mysqli_fetch_array($query_atracoes)) {
                                        $categorias = recuperaDados("categoria_atracoes", "id", $atracao['categoria_atracao_id']);

                                        ?>
                                        <p><?= $categorias['categoria_atracao'] . " - " . $evento['nome_evento'] ?></p>
                                        <hr>
                                        <?php
                                        $num_atracoes++;
                                    }
                                    echo "</td>";
                                    echo "<td>" . $locais . "</td>";
                                    echo "<td>" . retornaPeriodoNovo($evento['id']) . "</td>";
                                    echo "<td>" . $status['status'] . "</td>";
                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=agendao&p=resumo_evento_enviado\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='" . $evento['id'] . "'>
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
