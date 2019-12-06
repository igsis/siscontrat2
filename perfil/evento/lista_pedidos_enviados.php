<?php
include "includes/menu_principal.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

$idUser = $_SESSION['idUser'];
$sql = "SELECT eve.id AS id, eve.protocolo, ped.numero_processo, ped.pessoa_tipo_id, ped.pessoa_juridica_id, ped.pessoa_fisica_id, eve.nome_evento, ped.valor_total, pst.status 
        FROM eventos AS eve
        INNER JOIN pedidos AS ped ON eve.id = ped.origem_id
        INNER JOIN pedido_status AS pst ON ped.status_pedido_id = pst.id
        WHERE eve.publicado = 1 AND ped.publicado = 1 AND (ped.origem_tipo_id = 1 OR ped.origem_tipo_id = 2) AND evento_status_id >= 3 AND contratacao = 1 AND (suplente_id = '$idUser' OR fiscal_id = '$idUser' OR usuario_id = '$idUser')";
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
                                <th>Proponente</th>
                                <th width="15%">Objeto</th>
                                <th>Local</th>
                                <th>Valor</th>
                                <th width="17%">Período</th>
                                <th>Status</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>

                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)) {
                                $idEvento = $evento['id'];
                                if($evento['pessoa_tipo_id'] == 1){
                                    $pf = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id']);
                                    $proponente = $pf['nome'];
                                }else if($evento['pessoa_tipo_id' == 2]){
                                    $pj = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id']);
                                    $proponente = $pj['razao_social'];
                                }

                                $sql_atracao = "SELECT * FROM atracoes atr INNER JOIN categoria_atracoes cat ON cat.id = atr.categoria_atracao_id WHERE evento_id = '$idEvento' AND atr.publicado = 1";
                                $query_atracao = mysqli_query($con, $sql_atracao);

                                $locais = listaLocais($evento['id']);

                                // $locais = listaLocais($evento['idAtracao']);
                                echo "<tr>";
                                echo "<td>" . $evento['protocolo'] . "</td>";
                                echo "<td>" . $proponente . "</td>";
                                echo "<td>";
                                    echo $evento['nome_evento'];
                                echo "</td>";
                                echo "<td>" . $locais. "</td>";
                                echo "<td>" . dinheiroParaBr($evento['valor_total']) . "</td>";
                                echo "<td>" . retornaPeriodoNovo($idEvento, 'ocorrencias') . "</td>";
                                echo "<td>" . $evento['status'] . "</td>";
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
                                    <th>Proponente</th>
                                    <th>Objeto</th>
                                    <th>Local</th>
                                    <th>Valor</th>
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
