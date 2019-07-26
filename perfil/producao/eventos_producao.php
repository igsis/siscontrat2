<?php
include "../perfil/producao/includes/menu_interno.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$idUser = $_SESSION['idUser'];

$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['checar'])) {
    $idEvento = $_POST['idEvento'];
    $sqlPedido = "UPDATE eventos SET visualizado = 1 WHERE id = '$idEvento'";
    if (mysqli_query($con, $sqlPedido)) {
        $mensagem = mensagem("success", "Evento marcado como visualizado!");
    }
}

$sqlEvento = "SELECT
                    e.id AS 'id',
                    e.protocolo AS 'protocolo',
                    e.nome_evento AS 'nome_evento',
                    e.visualizado AS 'visualizado'
            FROM eventos AS e
            INNER JOIN pedidos AS p ON p.origem_id = e.id 
WHERE e.publicado = 1 AND e.evento_status_id = 3 AND p.status_pedido_id = 2";
$query = mysqli_query($con, $sqlEvento);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Eventos - Produção</h2>
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
                        <table id="tblEventosProducao" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Nome do Evento</th>
                                <th>Periodo</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($evento = mysqli_fetch_array($query)) {
                            if ($evento['visualizado'] != 1){
                            ?>
                            <tr style="background-color: rgba(255, 0, 0, 0.6);">
                                <?php
                                } else {
                                ?>
                            <tr>
                                <?php
                                }
                                echo "<td>" . $evento['protocolo'] . "</td>";
                                echo "<td>" . $evento['nome_evento'] . "</td>";
                                echo "<td>" . retornaPeriodoNovo($evento['id'], 'ocorrencias') . "</td>";
                                echo "<td>                               
                            <form method='POST' action='?perfil=producao&p=modulos&p=visualizacao_evento' role='form'>
                            <input type='hidden' name='idEvento' value='" . $evento['id'] . "'>
                            <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'> </span></button>
                            </form>
                            </td>";

                                echo "</tr>";
                                }
                                echo "</tbody>";
                                ?>
                                <tfoot>
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Nome do Evento</th>
                                    <th>Periodo</th>
                                    <th>Visualizar</th>
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
        $('#tblEventosProducao').DataTable({
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
