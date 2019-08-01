<?php
include "includes/menu_interno.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPf']);
unset($_SESSION['idPj']);

$idUser = $_SESSION['idUser'];

$con = bancoMysqli();
$conn = bancoPDO();

if (isset($_POST['checarAgendao'])) {
    $idEvento = $_POST['idEvento'];
    $sqlPedido = "UPDATE agendoes SET visualizado = 1 WHERE id = '$idEvento'";
    if (mysqli_query($con, $sqlPedido)) {
        $data = date("Y-m-d H:i:s", strtotime("now"));
        $sqlEnvio = "INSERT INTO producao_agendoes (agendao_id, usuario_id, data) VALUES ('$idEvento','$idUser','$data')";
        $queryEnvio = mysqli_query($con,$sqlEnvio);
        $mensagem = mensagem("success", "Agendão marcado como visualizado!");
    }
}

$sqlAgendaoVisualizado = "SELECT
	    a.id AS 'id',
		a.nome_evento AS 'nome',
		l.local AS 'local',
        esp.espaco AS 'espaco',
        a.data_envio AS 'data_envio'
		from agendoes AS a
        INNER JOIN agendao_ocorrencias AS ao ON ao.id = a.id
        LEFT JOIN locais AS l ON l.id = ao.local_id
        LEFT JOIN espacos AS esp ON esp.id = ao.espaco_id
        WHERE a.publicado = 1 AND a.visualizado = 1 AND evento_status_id = 3;";
$queryAgendaoVisualizado = mysqli_query($con, $sqlAgendaoVisualizado);

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header"> Agendões Visualizados </h2>
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
                        }
                        ?>
                    </div>

                    <div class="box-body">
                        <table id="tblAgendoesVisualizadosProducoes" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Locais</th>
                                <th>Espaços</th>
                                <th>Periodo</th>
                                <th>Data do Envio</th>
                                <th>Visualizar</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($agendaovisualizado = mysqli_fetch_array($queryAgendaoVisualizado)) {?>
                            <tr>

                            <?php
                                echo "<td>" . $agendaovisualizado['nome'] . "</td>";
                                echo "<td>" . $agendaovisualizado['local'] . "</td>";
                                echo "<td>" . $agendaovisualizado['espaco'] . "</td>";
                                echo "<td>" . retornaPeriodoNovo($agendaovisualizado['id'], 'agendao_ocorrencias') . "</td>";
                                echo "<td>" . $agendaovisualizado['data_envio'] . "</td>";
                                echo "<td>
                                        <form method='POST' action='?perfil=producao&p=modulos&p=visualizacao_agendao' role='form'>
                                        <input type='hidden' name='idEvento' value='" . $agendaovisualizado['id'] . "'>
                                        <button type='submit' name='carregaAgendao' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'></span>
                                        </button>
                                        </form> 
                                        </td>
                                        ";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Locais</th>
                                <th>Espaços</th>
                                <th>Periodo</th>
                                <th>Data do Envio</th>
                                <th>Visualizar</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
    </section>
</div>

<<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblAgendoesVisualizadosProducoes').DataTable({
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
?>
