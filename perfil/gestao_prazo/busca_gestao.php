<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$conn = bancoPDO();

$sql = "SELECT e.protocolo AS 'Protocolo', 
               e.nome_evento AS 'Nome do Evento',
               l.local AS 'Local',
               u.fiscal AS 'Fiscal',
               suplente.nome_completo AS 'Fiscal'
               FROM eventos AS e
               INNER JOIN ocorrencias AS o ON o.origem_ocorrencia_id = e.id
               INNER JOIN locais AS l ON l.id = o.local_id
               INNER JOIN usuarios AS u ON e.fiscal_id
               INNER JOIN usuarios AS suplente ON e.suplente_id
 WHERE evento_status_id = 2 AND e.publicado = 1;";



?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Eventos - Gestão</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de eventos fora do prazo</h3>
                    </div>
                    <div class="row" align="center">
                        <?php
                        if (isset($mensagem)) {
                            echo $mensagem;
                        };
                        ?>
                    </div>
                    <div class="box-body">
                        <table id="tblGestao" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Nome do Evento</th>
                                <th>Locais</th>
                                <th>Período</th>
                                <th>Fiscal</th>
                                <th>Suplente</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            $query = mysqli_query($con, $sql);
                            while ($eventos = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $eventos['protocolo'] . "</td>";
                                echo "<td>" . $eventos['nome_evento'] . "</td>";
                                echo "<td>" . $eventos['locais'] . "</td>";
                                echo "<td>" . retornaPeriodoNovo($eventos['id']) . "</td>";
                                echo "<td>" . $eventos['fiscal'] . "</td>";
                                echo "<td>" . $eventos['suplente'] . "</td>";
                                echo "<td>
                                                <form method='POST' action='' role=''>
                                                <input type='hidden' name='idEvento' value='" . $eventos['id'] . "'>
                                                <button type='submit' name='aprova' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'></span> </button>
                                                <button type='submit' name='revoga' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'></span> </button>                         
                                                </form>
                                        </td>";
                            }
                            echo "</tbody>"
                            ?>
                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Nome do Evento</th>
                                <th>Locais</th>
                                <th>Período</th>
                                <th>Fiscal</th>
                                <th>Suplente</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblGestao').DataTable({
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