<?php
include "includes/menu_interno.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$con = bancoMysqli();
$conn = bancoPDO();

$sqlAgendaoNovo = "SELECT
	    a.id AS 'id',
		a.nome_evento AS 'nome',
        a.data_envio AS 'data_envio',
        env.visualizado AS 'visualizado'
		from agendoes AS a
        JOIN agendao_ocorrencias AS ao ON ao.id = a.id
        INNER JOIN producao_agendoes AS env ON env.agendao_id = a.id
        WHERE a.publicado = 1 AND env.visualizado = 0 AND evento_status_id = 3";
$queryAgendaoNovo = mysqli_query($con, $sqlAgendaoNovo);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header"> Agendões Novos </h2>
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
                        <table id="tblAgendoesNovosProducoes" class="table table-bordered table-striped">
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
                            while ($agendaoNovo = mysqli_fetch_array($queryAgendaoNovo)) {
                                $idAgendao = $agendaoNovo['id'];
                                $sqlLocal = "SELECT l.local FROM locais l INNER JOIN agendao_ocorrencias ao ON ao.local_id = l.id WHERE ao.origem_ocorrencia_id = '$idAgendao'";
                                $queryLocal = mysqli_query($con, $sqlLocal);
                                $local = '';
                                while ($locais = mysqli_fetch_array($queryLocal)){
                                    $local = $local . '; ' . $locais['local'];
                                }
                                $local = substr($local, 1);

                                $sqlEspaco = "SELECT e.espaco FROM espacos AS e INNER JOIN agendao_ocorrencias AS ao ON ao.espaco_id = e.id WHERE ao.origem_ocorrencia_id = '$idAgendao'";
                                $queryEspaco = mysqli_query($con, $sqlEspaco);
                                $espaco = '';
                                while($espacos = mysqli_fetch_array($queryEspaco)){
                                    $espaco = $espaco . '; ' . $espacos['espaco'];
                                }
                                $espaco = substr($espaco, 1);
                            ?>
                            <tr>

                                <?php
                                echo "<td>" . $agendaoNovo['nome'] . "</td>";
                                echo "<td>" . $local . "</td>";
                                echo "<td>" . $espaco . "</td>";
                                echo "<td>" . retornaPeriodoNovo($agendaoNovo['id'], 'agendao_ocorrencias') . "</td>";
                                echo "<td>" . $agendaoNovo['data_envio'] . "</td>";
                                echo "<td>
                                        <form method='POST' action='?perfil=producao&p=modulos&p=visualizacao_agendao' role='form'>
                                        <input type='hidden' name='idEvento' value='" . $agendaoNovo['id'] . "'>
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
        </div>
    </section>
</div>


<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblAgendoesNovosProducoes').DataTable({
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