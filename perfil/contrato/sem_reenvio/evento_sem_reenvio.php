<?php
$con = bancoMysqli();

$sql = "SELECT e.id, e.protocolo, e.nome_evento,  er.data_reabertura, e.usuario_id, er.usuario_reabertura_id, p.pessoa_tipo_id, p.pessoa_juridica_id, p.pessoa_fisica_id, p.valor_total, p.operador_id
        FROM eventos e 
        INNER JOIN evento_envios ee ON e.id = ee.evento_id 
        INNER JOIN evento_reaberturas er on e.id = er.eventos_id
        INNER JOIN pedidos p ON p.origem_id = e.id
        WHERE er.data_reabertura > ee.data_envio and e.publicado = 1 AND p.origem_tipo_id = 1";

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
                                <th>Data reabertura</th>
                                <th>Reaberto por</th>
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
                                            <form action="#" method="POST">
                                                <input type="hide" name="idEvento" id="idEvento" value="<?= $evento['id'] ?>">
                                                <button type="button" class="btn btn-link"><?= $evento['protocolo'] ?></button>
                                            </form>
                                        </td>
                                        <td><?= $baldeProponente ?></td>
                                        <td><?= $evento['nome_evento'] ?></td>
                                        <td><?= dinheiroParaBr($evento['valor_total']) ?></td>
                                        <td><?= exibirDataBr($evento['data_reabertura']) ?></td>
                                        <td><?= recuperaDados('usuarios', 'id', $evento['usuario_reabertura_id'])['nome_completo']; ?></td>
                                        <td><?= $recuperaDados('usuarios', 'id', $evento['operador_id'])['nome_completo']; ?></td>
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
                                <th>Data reabertura</th>
                                <th>Reaberto por</th>
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


