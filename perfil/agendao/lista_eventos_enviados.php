<?php

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

$idUser = $_SESSION['usuario_id_s'];
$sql = "SELECT * FROM agendoes WHERE publicado = 1 AND evento_status_id = 3 AND usuario_id = '$idUser'";
$query = mysqli_query($con, $sql);
$linha = mysqli_num_rows($query);

$num_atracoes = 0;

?>

<!-- START FORM-->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h4>Lista de Agendões</h4>
                    </div>
                        <div class="row" align="center">
                            <?php if (isset($mensagem)) {
                                echo $mensagem;
                            }; ?>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="tblAgendao" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nome do evento</th>
                                    <th>Local</th>
                                    <th>Período</th>
                                    <th>Visualizar</th>
                                </tr>
                                </thead>

                                <?php
                                echo "<tbody>";
                                while ($evento = mysqli_fetch_array($query)) {
                                    $idEvento = $evento['id'];
                                    $sqlLocal = "SELECT l.local FROM locais l INNER JOIN agendao_ocorrencias ao ON ao.local_id = l.id WHERE ao.origem_ocorrencia_id = '$idEvento' AND ao.publicado = 1";
                                    $queryLocal = mysqli_query($con, $sqlLocal);
                                    $local = '';
                                    while ($locais = mysqli_fetch_array($queryLocal)) {
                                        $local = $local . '; ' . $locais['local'];
                                    }
                                    $local = substr($local, 1);
                                
                                    echo "<tr>";
                                    echo "<td>". $evento['nome_evento']."</td>";
                                    echo "<td>" . $local . "</td>";
                                    echo "<td>" . retornaPeriodoNovo($evento['id'], 'agendao_ocorrencias') . "</td>";
                                    echo "<td>
                                    <form method=\"POST\" action=\"?perfil=agendao&p=resumo_evento_enviado\" role=\"form\">
                                    <input type='hidden' name='idEvento' value='" . $evento['id'] . "'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><i class='fa fa-file-text-o'></i></button>
                                    </form>
                                </td>";
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


<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblAgendao').DataTable({
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
