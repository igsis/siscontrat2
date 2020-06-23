<?php
$con = bancoMysqli();

if (isset($_POST['busca'])) {
    $data_inicio = $_POST['data_inicio'] ?? NULL;
    $data_fim = $_POST['data_fim'] ?? NULL;
    $operador = $_POST['operador'] ?? NULL;

    $sqlOperador = '';

    if ($operador != null && $operador != 0)
        $sqlOperador = " AND p.operador_id = '$operador'";

    $sql = "SELECT e.id, e.protocolo, p.numero_processo, p.pessoa_tipo_id, 
    p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, 
    p.valor_total, e.evento_status_id, p.operador_id, ps.status,
    p.pendencias_contratos
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    INNER JOIN ocorrencias o on e.id = o.origem_ocorrencia_id
    INNER JOIN evento_envios ee ON e.id = ee.evento_id 
    LEFT JOIN evento_reaberturas er on e.id = er.evento_id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND p.status_pedido_id != 3
    AND p.status_pedido_id != 1
    AND o.data_inicio >= '$data_inicio'
    AND o.data_fim <= '$data_fim'
    AND e.evento_status_id != 1
    AND (
      (er.data_reabertura < ee.data_envio) 
    OR 
      (er.data_reabertura is null)
    )
    $sqlOperador
    GROUP BY e.id";

    $query = mysqli_query($con, $sql);
    $num_rows = mysqli_num_rows($query);
}

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Resultado de busca</h3>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Número de processo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Local(ais)</th>
                                <th>Instituição(ões)</th>
                                <th>Período</th>
                                <th>Pendências</th>
                                <th>Status</th>
                                <th>Operador</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_rows == 0) {
                                ?>
                                <tr>
                                    <th colspan="7"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($evento = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <?php
                                        //Locais
                                        $sqlLocal = "SELECT l.local FROM locais AS l INNER JOIN ocorrencias AS o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = " . $evento['id'] . " AND o.publicado = 1";
                                        $local = "";
                                        $queryLocal = mysqli_query($con, $sqlLocal);

                                        while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                            $local = $local . $linhaLocal['local'] . ' | ';
                                        }

                                        $local = substr($local, 0, -3);

                                        //Proponente
                                        if ($evento['pessoa_tipo_id'] == 1){
                                            $pessoa = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id'])['nome'];
                                        }
                                        else if ($evento['pessoa_tipo_id'] == 2){
                                            $pessoa = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id'])['razao_social'];
                                        }

                                        //Instituições
                                        $sqlInst = "SELECT i.nome FROM instituicoes AS i INNER JOIN ocorrencias AS o ON o.instituicao_id = i.id WHERE o.origem_ocorrencia_id = " . $evento['id'] . " AND o.publicado = 1";
                                        $inst = "";
                                        $queryInst = mysqli_query($con, $sqlInst);

                                        while ($linhaInst = mysqli_fetch_array($queryInst)) {
                                            $inst = $inst . $linhaInst['nome'] . '; ';
                                        }

                                        $inst = substr($inst, 0);

                                        if ($evento['pessoa_tipo_id'] == 1)
                                            $pessoa = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id'])['nome'];
                                        else if ($evento['pessoa_tipo_id'] == 2)
                                            $pessoa = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id'])['razao_social'];

                                        ?>
                                        <td>
                                            <form method="POST" action="?perfil=contrato&p=resumo">
                                                <input type="hidden" name="idEvento" id="idEvento"
                                                       value="<?= $evento['id'] ?>">
                                                <button type="submit"
                                                        class="btn btn-link" name="load"><?= $evento['protocolo'] ?></button>
                                            </form>
                                        </td>
                                        <td><?= $evento['numero_processo'] ?></td>
                                        <td><?= $pessoa ?></td>
                                        <td><?= $evento['nome_evento'] ?></td>
                                        <td>R$ <?= dinheiroParaBr($evento['valor_total']) ?></td>
                                        <td><?= $local ?></td>
                                        <td><?= $inst ?></td>
                                        <td><?= retornaPeriodoNovo($evento['id'], "ocorrencias")?></td>
                                        <td><?= $evento['pendencias_contratos'] ? "" : "Não possui" ?></td>
                                        <td><?= $evento['status'] ?></td>
                                        <?php
                                            if($evento['operador_id'] != NULL){
                                                $operador = recuperaDados('usuarios', 'id', $evento['operador_id'])['nome_completo'];
                                            }else{
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
                                <th>Número de processo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Local(ais)</th>
                                <th>Instituição(ões)</th>
                                <th>Período</th>
                                <th>Pendências</th>
                                <th>Status</th>
                                <th>Operador</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=contrato&p=pesquisa_periodo">
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