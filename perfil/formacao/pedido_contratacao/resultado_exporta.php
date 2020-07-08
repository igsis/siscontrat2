<?php
$con = bancoMysqli();

$protocolo = $_POST['protocolo'];
$processo = $_POST['processo'];
$proponente = $_POST['proponente'];

$sqlProtocolo = "";
$sqlProcesso = "";
$sqlProponente = "";

if ($protocolo != NULL) {
    $sqlProtocolo = "AND fc.protocolo LIKE '%$protocolo%'";
}

if ($processo != NULL) {
    $sqlProcesso = "AND pc.numero_processo LIKE '%$processo%'";
}

if ($proponente != NULL) {
    $sqlProponente = "AND pc.pessoa_fisica_id = $proponente";
}

$sql = "SELECT pc.id,
                   fc.protocolo,
                   pc.numero_processo,
                   pf.nome, 
	               p.programa,
                   c.cargo,
                   l.linguagem,
                   st.status
            FROM pedidos AS pc
            INNER JOIN formacao_contratacoes fc ON fc.id = pc.origem_id
            INNER JOIN pessoa_fisicas AS pf ON pf.id = fc.pessoa_fisica_id
            INNER JOIN programas AS p ON p.id = fc.programa_id
	        INNER JOIN formacao_cargos AS c ON c.id = fc.form_cargo_id
            INNER JOIN linguagens AS l ON l.id = fc.linguagem_id
            INNER JOIN formacao_status AS st ON st.id = fc.form_status_id 
            WHERE pc.origem_tipo_id = 2 AND pc.publicado = 1
            $sqlProcesso $sqlProponente $sqlProtocolo
            GROUP BY pc.id";

$query = mysqli_query($con, $sql);
?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header">Formação - Exportar para Excel</h3>
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Resumo da pesquisa</h3>
            </div>
            <div class="box-body">
                <table id="tblResultadoFormacao" class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Processo</th>
                        <th>Proponente</th>
                        <th>Programa</th>
                        <th>Função</th>
                        <th>Linguagem</th>
                        <th>Status</th>
                        <th>Exportar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($pc = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?= $pc['protocolo'] ?></td>
                            <td><?= $pc['numero_processo'] ?></td>
                            <td><?= $pc['nome'] ?></td>
                            <td><?= $pc['programa'] ?></td>
                            <td><?= $pc['cargo'] ?></td>
                            <td><?= $pc['linguagem'] ?></td>
                            <td><?= $pc['status'] ?></td>
                            <td>
                                <form action="../pdf/exportar_excel_pedido_formacao.php" method="post" target="_blank">
                                    <input type="hidden" name="idPedido" value="<?= $pc['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <span class="glyphicon glyphicon-list-alt"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th>Protocolo</th>
                        <th>Processo</th>
                        <th>Proponente</th>
                        <th>Programa</th>
                        <th>Função</th>
                        <th>Linguagem</th>
                        <th>Status</th>
                        <th>Exportar</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=formacao&p=pedido_contratacao&sp=pesquisa">
                    <button type="button" class="btn btn-default">Voltar para a pesquisa</button>
                </a>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblResultadoFormacao').DataTable({
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
