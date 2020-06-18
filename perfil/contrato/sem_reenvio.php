<?php
$con = bancoMysqli();

$sql = "SELECT e.id, e.protocolo, e.nome_evento,  er.data_reabertura, e.usuario_id, er.usuario_reabertura_id, p.pessoa_tipo_id, p.pessoa_juridica_id, p.pessoa_fisica_id, p.valor_total, p.operador_id
        FROM eventos e 
        INNER JOIN evento_envios ee ON e.id = ee.evento_id 
        LEFT JOIN evento_reaberturas er on e.id = er.evento_id
        INNER JOIN pedidos p ON p.origem_id = e.id
        WHERE e.publicado = 1 
        AND er.evento_id IS NULL
        AND p.publicado = 1
        AND p.origem_tipo_id = 1
        AND e.evento_status_id != 1 
        AND p.status_pedido_id != 3 GROUP BY e.id";


$query = mysqli_query($con, $sql);
$rows = mysqli_num_rows($query);

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista </h3>
        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Local(ais)</th>
                                <th>Período</th>
                                <th>Prazo (dias)</th>
                                <th>Operador</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($rows == 0) {
                                ?>
                                <tr>
                                    <th colspan="10"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($evento = mysqli_fetch_array($query)) {
                                    //Locais
                                    $sqlLocal = "SELECT l.local FROM locais AS l INNER JOIN ocorrencias AS o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = " . $evento['id'] . " AND o.publicado = 1";
                                    $local = "";
                                    $queryLocal = mysqli_query($con, $sqlLocal);

                                    while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                        $local = $local . $linhaLocal['local'] . ' | ';
                                    }

                                    $local = substr($local, 0, -3);

                                    //calcula a diferença entre hoje e a data inicial do evento
                                    $inicial = $con->query("SELECT data_inicio FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id'] . " AND publicado = '1' ORDER BY data_inicio ASC LIMIT 0,1")->fetch_array()['data_inicio'] ?? NULL;
                                    $hoje = date("Y-m-d");
                                    $diferenca = strtotime($inicial) - strtotime($hoje);
                                    $prazo = floor($diferenca / (60 * 60 * 24));

                                    //proponente
                                    $baldeProponente = '';
                                    if ($evento['pessoa_tipo_id'] == 1) {
                                        $pf = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id']);
                                        $baldeProponente = $pf['nome'];
                                    } else {
                                        $pj = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id']);
                                        $baldeProponente = $pj['razao_social'];
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <form action="?perfil=contrato&p=resumo" method="POST">
                                                <input type="hidden" name="idEvento" id="idEvento" value="<?= $evento['id'] ?>">
                                                <button type="submit" class="btn btn-link" name="load"><?= $evento['protocolo'] ?></button>
                                            </form>
                                        </td>
                                        <td><?= $baldeProponente ?></td>
                                        <td><?= $evento['nome_evento'] ?></td>
                                        <td><?= dinheiroParaBr($evento['valor_total']) ?></td>
                                        <td><?= $local ?></td>
                                        <td><?= retornaPeriodoNovo($evento['id'], 'ocorrencias')?></td>
                                        <td><?= $prazo ?></td>
                                        <?php
                                        if ($evento['operador_id'] != NULL) {
                                            $operador = recuperaDados('usuarios', 'id', $evento['operador_id'])['nome_completo'];
                                        } else {
                                            $operador = "Não possui";
                                        }
                                        ?>
                                        <td><?= $operador ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                            <th>Protocolo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Local(ais)</th>
                                <th>Período</th>
                                <th>Prazo (dias)</th>
                                <th>Operador</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=contrato">
                            <button type="button" class="btn btn-default">Voltar</button>
                        </a>
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
        $('#tblResultado').DataTable({
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
