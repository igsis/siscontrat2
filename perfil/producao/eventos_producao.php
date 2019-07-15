<?php
include "../perfil/producao/includes/menu_interno.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['excluir'])) {
    $evento = $_POST['idEvent'];
    $stmt = $conn->prepare("UPDATE eventos SET publicado = 0 WHERE  id = :id");
    $stmt->execute(['id' => $evento]);
    $mensagem = mensagem("sucess", "Evento excluido com sucesso!");

}
$idUser = $_SESSION['idUser'];
$sql = "SELECT ev.id AS idEvento, ev.nome_evento, te.tipo_evento, es.status FROM eventos AS ev
        INNER JOIN tipo_eventos AS te on ev.tipo_evento_id = te.id
        INNER JOIN evento_status es on ev.evento_status_id = es.id
        WHERE publicado <> 0 AND (usuario_id = '$idUser' OR fiscal_id = '$idUser' OR suplente_id = '$idUser') AND evento_status_id = 3";

$query = mysqli_query($con, $sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Eventos - Producao</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>

                    <div class="row" align="center">
                        <?php
                        if (isset($mensagem)) {
                            echo $mensagem;
                        } ?>
                    </div>

                    <div class="box-body">
                        <table id="tblEvento" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome do evento</th>
                                <th>Tipo do evento</th>
                                <th>Status</th>
                                <th>Visualizar</th>

                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $evento['nome_evento'] . "</td>";
                                echo "<td>" . $evento['tipo_evento'] . "</td>";
                                echo "<td>" . $evento['status'] . "</td>";
                                echo "<td>                               
                            <form method='POST' action='?perfil=producao&p=modulos&p=visualizacao_evento' role='form'>
                            <input type='hidden' name='idEvento' value='" . $evento['idEvento'] . "'>
                            <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'> </span></button>
                            </form>
                            </td>";

                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Tipo do Evento</th>
                                <th>Status</th>

                            </tr>
                            </tfoot>


                        </table>

                    </div>

                </div>
            </div>


    </section>
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
