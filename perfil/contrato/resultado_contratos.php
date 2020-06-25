<?php
$con = bancoMysqli();
$link_api_locais_instituicoes = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_listar_locais_instituicoes.php';

unset($_SESSION['idEvento']);

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

    $sql = "SELECT e.id, e.protocolo, p.numero_processo, p.pessoa_tipo_id, 
    p.pessoa_fisica_id, p.pessoa_juridica_id, e.nome_evento, 
    p.valor_total, e.evento_status_id, p.operador_id, ps.status,
    p.pendencias_contratos
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    INNER JOIN evento_envios ee ON e.id = ee.evento_id 
    LEFT JOIN evento_reaberturas er on e.id = er.evento_id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND p.status_pedido_id != 1
    AND p.status_pedido_id != 3
    AND e.evento_status_id != 1 
    AND (
      (er.data_reabertura < ee.data_envio) 
    OR 
      (er.data_reabertura is null)
    )
    $sqlProjeto $sqlUsuario $sqlStatus 
    $sqlProtocolo $sqlNomeEvento $sqlProcesso
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
                                    //Proponente
                                    if ($evento['pessoa_tipo_id'] == 1) {
                                        $pessoa = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id'])['nome'];
                                    } else if ($evento['pessoa_tipo_id'] == 2) {
                                        $pessoa = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id'])['razao_social'];
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <form method="POST" action="?perfil=contrato&p=resumo">
                                                <input type="hidden" name="idEvento"
                                                       value="<?= $evento['id'] ?>">
                                                <button type="submit" class="btn btn-link"
                                                        name="load"><?= $evento['protocolo'] ?></button>
                                            </form>
                                        </td>
                                        <td><?= $evento['numero_processo'] == NULL ? "Não cadastrado" : $evento['numero_processo'] ?></td>
                                        <td><?= $pessoa ?></td>
                                        <td><?= $evento['nome_evento'] ?></td>
                                        <td>R$ <?= dinheiroParaBr($evento['valor_total']) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-block" id="exibirLocais"
                                                    data-toggle="modal" data-target="#modalLocais_Inst" data-name="local"
                                                    onClick="exibirLocal_Instituicao('<?=$link_api_locais_instituicoes?>', '#modalLocais_Inst', '#modalTitulo')"
                                                    data-id="<?= $evento['id'] ?>"
                                                    name="exibirLocais">
                                                Clique para ver os locais
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-block"
                                                    id="exibirInstituicoes"
                                                    data-toggle="modal" data-target="#modalLocais_Inst"
                                                    onClick="exibirLocal_Instituicao('<?=$link_api_locais_instituicoes?>', '#modalLocais_Inst', '#modalTitulo')"
                                                    data-name="inst"
                                                    data-id="<?= $evento['id'] ?>"
                                                    name="exibirInstituicoes">
                                                Clique para ver as instituições
                                            </button>
                                        </td>
                                        <td><?= retornaPeriodoNovo($evento['id'], "ocorrencias") ?></td>
                                        <td><?= $evento['pendencias_contratos'] ? "" : "Não possui" ?></td>
                                        <td><?= $evento['status'] ?></td>
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
                        <a href="?perfil=contrato&p=pesquisa_contratos">
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
