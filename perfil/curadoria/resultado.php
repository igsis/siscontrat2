<?php
$con = bancoMysqli();

if (isset($_POST['busca'])) {
    $protocolo = $_POST['protocolo'] ?? NULL;
    $nomeEvento = $_POST['nomeEvento'] ?? NULL;
    $usuario = $_POST['usuario'] ?? NULL;
    $projeto = $_POST['projeto'] ?? NULL;
    $status = $_POST['status'] ?? NULL;

    $sqlProtocolo = '';
    $sqlNomeEvento = '';
    $sqlProjeto = '';
    $sqlUsuario = '';
    $sqlStatus = '';

    if ($protocolo != null)
        $sqlProtocolo = " AND e.protocolo LIKE '%$protocolo%'";
    if ($nomeEvento != null)
        $sqlNomeEvento = " AND e.nome_evento LIKE '%$nomeEvento%'";
    if ($projeto != null && $projeto != 0)
        $sqlProjeto = " AND e.projeto_especial_id = '$projeto'";
    if ($usuario != null && $usuario != 0)
        $sqlUsuario = " AND fiscal_id = '$usuario' OR suplente_id = '$usuario' OR usuario_id = '$usuario'";
    if ($status != null && $status != 0)
        $sqlStatus = " AND evento_status_id = '$status'";

    $sql = "SELECT e.id, p.id AS idPedido, 
                   e.protocolo, p.numero_processo, 
                   e.nome_evento, p.valor_total,
                   fiscal.nome_completo AS 'fiscal'
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN pedido_status ps on p.status_pedido_id = ps.id
    INNER JOIN usuarios AS fiscal ON e.fiscal_id = fiscal.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.origem_tipo_id = 1
    AND p.status_pedido_id NOT IN (1,3,20,21)
    $sqlProjeto $sqlUsuario $sqlStatus
    $sqlProtocolo $sqlNomeEvento";
    $resultado = $con->query($sql);
}
?>
<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Resultado de busca</h3>
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
                                <th>Processo</th>
                                <th>Nome do evento</th>
                                <th>Fiscal</th>
                                <th>Local</th>
                                <th>Período</th>
                                <th>Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($evento = mysqli_fetch_array($resultado)) {
                                $sqlLocal = "SELECT l.local FROM locais l INNER JOIN ocorrencias o ON o.local_id = l.id WHERE o.origem_ocorrencia_id = " . $evento['id'] . " AND o.publicado = 1";
                                $queryLocal = mysqli_query($con, $sqlLocal);
                                $local = '';
                                while ($locais = mysqli_fetch_array($queryLocal)) {
                                    $local = $local . '; ' . $locais['local'];
                                }
                                $local = substr($local, 1);
                                ?>
                                <tr>
                                    <td><?= $evento['protocolo'] ?></td>
                                    <td>
                                        <form action="?perfil=curadoria&p=resumo" method="POST">
                                            <input type="hidden" name="idEvento" value="<?= $evento['id'] ?>">
                                            <button type="submit" name="carregar" id="carregar"
                                                    class="btn btn-primary"><?= $evento['numero_processo'] ?></button>
                                        </form>
                                    </td>
                                    <td><?= $evento['nome_evento'] ?></td>
                                    <td><?= $evento['fiscal'] ?></td>
                                    <td><?= $local ?></td>
                                    <td><?= retornaPeriodoNovo($evento['id'], 'ocorrencias') ?></td>
                                    <td><?= dinheiroParaBr($evento['valor_total']) ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Processo</th>
                                <th>Nome do evento</th>
                                <th>Fiscal</th>
                                <th>Local</th>
                                <th>Período</th>
                                <th>Valor</th>
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