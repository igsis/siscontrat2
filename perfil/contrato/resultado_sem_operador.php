<?php
$con = bancoMysqli();

$link_api_locais_instituicoes = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_listar_locais_instituicoes.php';

if (isset($_POST['busca'])) {
    $protocolo = $_POST['protocolo'] ?? NULL;
    $num_processo = $_POST['num_processo'] ?? NULL;
    $nomeEvento = $_POST['evento'] ?? NULL;
    $projeto = $_POST['projeto'] ?? NULL;
    $usuario = $_POST['usuario'] ?? NULL;
    $status = $_POST['status'] ?? NULL;

    $sqlProcesso = '';
    $sqlNomeEvento = '';
    $sqlProtocolo = '';
    $sqlProjeto = '';
    $sqlStatus = '';
    $sqlUsuario = '';

    if ($protocolo != null)
        $sqlProtocolo = " AND e.protocolo LIKE '%$protocolo%'";

    if ($num_processo != null)
        $sqlProcesso = " AND p.numero_processo LIKE '%$num_processo%'";

    if ($nomeEvento != null)
        $sqlNomeEvento = " AND e.nome_evento LIKE '%$nomeEvento%'";

    if ($projeto != null && $projeto != 0)
        $sqlProjeto = " AND e.projeto_especial_id = '$projeto'";

    if ($status != null && $status != 0)
        $sqlStatus = " AND p.status_pedido_id = '$status'";

    if ($usuario != null && $usuario != 0)
        $sqlUsuario = " AND (fiscal_id = '$usuario' OR suplente_id = '$usuario' OR usuario_id = '$usuario')";

    $sql = "SELECT e.id,
                   p.id as 'pedido_id',
                   e.protocolo,
                   p.numero_processo, 
                   p.pessoa_tipo_id,
                   p.pessoa_fisica_id, 
                   p.pessoa_juridica_id, 
                   e.nome_evento,
                   p.valor_total, 
                   p.status_pedido_id, 
                   e.usuario_id,
                   ps.status
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    INNER JOIN evento_envios ee ON e.id = ee.evento_id 
    LEFT JOIN evento_reaberturas er on e.id = er.evento_id
    WHERE e.publicado = 1 
    AND e.evento_status_id != 1 
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND p.status_pedido_id != 3
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1 
    AND p.operador_id IS NULL
    AND (
      (er.data_reabertura < ee.data_envio) 
    OR 
      (er.data_reabertura is null)
    )
    $sqlProjeto $sqlUsuario $sqlStatus 
    $sqlProtocolo $sqlNomeEvento $sqlProcesso
    GROUP BY e.id";

    $query = mysqli_query($con, $sql);
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
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Local</th>
                                <th width="14%">Período</th>
                                <th>Data de Envio</th>
                                <th>Prazo (dias)</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            while ($evento = mysqli_fetch_array($query)) {
                                if ($evento['pessoa_tipo_id'] == 1)
                                    $pessoa = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id'])['nome'];
                                else if ($evento['pessoa_tipo_id'] == 2)
                                    $pessoa = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id'])['razao_social'];
                                $idEvento = $evento['id'];

                                //calcula a diferença entre hoje e a data inicial do evento
                                $inicial = $con->query("SELECT data_inicio FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id'] . " AND publicado = '1' ORDER BY data_inicio ASC LIMIT 0,1")->fetch_array()['data_inicio'] ?? NULL;
                                $hoje = date("Y-m-d");
                                $diferenca = strtotime($inicial) - strtotime($hoje);
                                $prazo = floor($diferenca / (60 * 60 * 24));

                                //consulta ultima data de envio
                                $envio = $con->query("SELECT data_envio FROM evento_envios WHERE evento_id = " . $evento['id'] . " ORDER BY data_envio DESC LIMIT 0,1")->fetch_array()['data_envio'];
                                ?>
                                <tr>
                                    <?php

                                    if ($evento['protocolo'] != NULL) {
                                        ?>
                                        <td>
                                            <form action="?perfil=contrato&p=resumo" role="form" method="POST">
                                                <input type="hidden" id="idPedido" name="idPedido"
                                                       value="<?= $evento['pedido_id'] ?>">
                                                <input type="hidden" name="idEvento" id="idEvento"
                                                       value="<?= $evento['id'] ?>">
                                                <button type="submit" class="btn btn-link"
                                                        name="load"><?= $evento['protocolo'] ?></button>
                                            </form>
                                        </td>
                                        <?php
                                    } else {
                                        ?>
                                        <td></td>
                                    <?php }
                                    ?>
                                    <td><?= $pessoa ?></td>
                                    <td><?= $evento['nome_evento'] ?></td>
                                    <td>R$ <?= dinheiroParaBr($evento['valor_total']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-block" id="exibirLocais"
                                                data-toggle="modal" data-target="#modalLocais_Inst" data-name="local"
                                                onClick="exibirLocal_Instituicao('<?= $link_api_locais_instituicoes ?>', '#modalLocais_Inst', '#modalTitulo')"
                                                data-id="<?= $evento['id'] ?>"
                                                name="exibirLocais">
                                            Ver locais
                                        </button>
                                    </td>
                                    <td> <?= retornaPeriodoNovo($evento['id'], 'ocorrencias') ?> </td>
                                    <td><?= exibirDataBr($envio) ?></td>
                                    <td><?= $prazo ?></td>
                                    <td><?= $evento['status'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Proponente</th>
                                <th>Nome do evento</th>
                                <th>Valor</th>
                                <th>Local</th>
                                <th width="14%">Período</th>
                                <th>Data de Envio</th>
                                <th>Prazo (dias)</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=contrato&p=pesquisa_sem_operador">
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
