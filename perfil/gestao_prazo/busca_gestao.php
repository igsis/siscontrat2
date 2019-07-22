<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$conn = bancoPDO();
$sql = "SELECT
               e.id AS 'id',
               e.protocolo AS 'protocolo', 
               e.nome_evento AS 'nome_evento',
               l.local AS 'local',
               e.suplente_id,
               e.fiscal_id
               FROM eventos AS e
               INNER JOIN pedidos AS p ON p.origem_id = e.id 
               INNER JOIN ocorrencias AS o ON o.origem_ocorrencia_id = e.id
               INNER JOIN locais AS l ON l.id = o.local_id
               WHERE evento_status_id = 3 AND e.publicado = 1 AND p.status_pedido_id = 1";

if(isset($_POST['vetar'])){
    $idEvento = $eventos['id'] ;
    $sqlDelete = "DELETE FROM eventos WHERE id = '$idEvento'";
    if(mysqli_query($con, $sqlDelete)){
        $mensagem = mensagem("success", "Evento deletado com sucesso!");
    }
}

$query = mysqli_query($con, $sql);
?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Pedidos - Gestão</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Lista de pedidos fora do prazo</h3>
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
                            </tr>
                            </thead>
                            <?php
                            echo "<tbody>";
                            while ($eventos = mysqli_fetch_array($query)) {
                                $suplente = recuperaDados('usuarios', 'id', $eventos['suplente_id']);
                                $fiscal = recuperaDados('usuarios', 'id', $eventos['fiscal_id']);
                                echo "<tr>";
                                echo "<td>" . $eventos['protocolo'] . "</td>";
                                echo "<td>" . $eventos['nome_evento'] . "</td>";
                                echo "<td>" . $eventos['local'] . "</td>";
                                echo "<td>" . retornaPeriodoNovo($eventos['id']) . "</td>";
                                echo "<td>" . $suplente['nome_completo'] . "</td>";
                                echo "<td>" . $suplente['nome_completo'] . "</td>";
                                echo "<td>
                                                <form method='POST' action='?perfil=gestao_prazo&p=detalhes_gestao' role='form'>
                                                <input type='hidden' name='idEvento' value='" . $eventos['id'] . "'>
                                                <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'></span> </button>
                                                
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