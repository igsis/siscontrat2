<?php
$con = bancoMysqli();

if (isset($_POST['busca'])) {
    $codigopedido = $_POST['codigopedido'] ?? NULL;
    $numprocesso = $_POST['numprocesso'] ?? NULL;
    $statuspedido = $_POST['statuspedido'] ?? NULL;

    $sqlCodigo = '';
    $sqlnumProcesso = '';
    $sqlStatus = '';

    if ($codigopedido != NULL)
        $sqlCodigo = " AND protocolo LIKE '%$codigopedido%'";
    if ($numprocesso != NULL)
        $sqlnumProcesso = "AND numero_processo LIKE '%$numprocesso%'";
    if ($statuspedido != NULL)
        $sqlStatus = "AND ps.id =$statuspedido";
}

$sql = "select fc.protocolo,p.numero_processo, pf.nome, fc.id,ps.status
        from formacao_contratacoes as fc
        INNER JOIN pedidos as p on p.origem_id = fc.id
        INNER JOIN pedido_status as ps on p.status_pedido_id = ps.id
        INNER JOIN formacao_status as fs on fc.form_status_id = fs.id
        INNER JOIN pessoa_fisicas as pf on p.pessoa_fisica_id = pf.id
        WHERE p.publicado = 1 AND p.origem_tipo_id = 2 AND fc.publicado = 1 $sqlCodigo $sqlnumProcesso $sqlStatus";
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Buscar por Formação</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Resultado da pesquisa</h3>
            </div>
            <div class="box-body">
                <table id="tblEventos" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Processo</th>
                        <th>Protocolo</th>
                        <th>Proponente</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($query = mysqli_query($con, $sql)) {
                        while ($formacao = mysqli_fetch_array($query)) {
                            $_SESSION['formacaoId'] = $formacao['id'];
                            ?>
                            <tr>
                                <?php
                                if (isset($formacao['numero_processo'])) {
                                    ?>
                                    <td>
                                        <form action="?perfil=juridico&p=filtrar_formacao&sp=seleciona_modelo_formacao"
                                              role="form" method="POST">
                                            <input type="hidden" value="<?= $formacao['id'] ?>" name="idFormacao">
                                            <button type="submit"
                                                    class="btn btn-link"><?= $formacao['numero_processo'] ?></button>
                                        </form>
                                    </td>
                                    <?php
                                } else {
                                    echo "<td> Não possui </td>";
                                }
                                ?>
                                <td><?= $formacao['protocolo'] ?></td>
                                <td><?= $formacao['nome'] ?></td>
                                <td><?= $formacao['status'] ?></td>

                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Processo</th>
                        <th>Protocolo</th>
                        <th>Proponente</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=juridico&p=filtrar_formacao&sp=pesquisa_formacao">
                    <button type="button" class="btn btn-default">Voltar a pesquisa</button>
                </a>
            </div>
        </div>
    </section>
</div>
<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblEventos').DataTable({
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