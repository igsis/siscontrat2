<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$conn = bancoPDO();
$sql = "SELECT
               e.id,
               e.protocolo AS 'Protocolo', 
               e.nome_evento AS 'Nome do Evento',
               l.local AS 'Local',
               u.fiscal AS 'Fiscal',
               suplente.nome_completo AS 'Suplente'
               FROM eventos AS e
               INNER JOIN pedidos AS p ON p.origem_id = e.id 
               INNER JOIN ocorrencias AS o ON o.origem_ocorrencia_id = e.id
               INNER JOIN locais AS l ON l.id = o.local_id
               INNER JOIN usuarios AS u ON e.fiscal_id
               INNER JOIN usuarios AS suplente ON e.suplente_id
               WHERE evento_status_id = 3 AND e.publicado = 1 AND p.status_pedido_id = 1";



$query = mysqli_query($con, $sql);
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
                                <th>Visualizar</th>
                                <th>Deletar</th>
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";

                            while ($eventos = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $eventos['Protocolo'] . "</td>";
                                echo "<td>" . $eventos['Nome do Evento'] . "</td>";
                                echo "<td>" . $eventos['Local'] . "</td>";
                                echo "<td>" . retornaPeriodoNovo($eventos['id']) . "</td>";
                                echo "<td>" . $eventos['Fiscal'] . "</td>";
                                echo "<td>" . $eventos['Suplente'] . "</td>";
                                echo "<td>
                                                <form method='POST' action='' role=''>
                                                <input type='hidden' name='idEvento' value='" . $eventos['id'] . "'>
                                                <button type='submit' name='aprova' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'></span> </button>
                                                
                                        </td>";
                                echo "<td>
                                                    <button type='button' name='revoga' class='btn btn-block btn-danger' id='excluiEvento'
                                                        data-toggle='modal' data-target='#exclusao' name='excluiEvento'
                                                        ><span class='glyphicon glyphicon-trash'></span></button>                         
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
                                <th>Visualizar</th>
                                <th>Deletar</th>
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