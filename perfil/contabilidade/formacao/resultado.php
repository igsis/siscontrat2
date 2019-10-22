<?php
$con = bancoMysqli();

$protocolo = '';
$numProcesso = '';
$status = '';
$where = '';

if (isset($_POST['protocolo']) && $_POST['protocolo'] != null) {
    $protocolo = $_POST['protocolo'];
    $protocolo = " AND p.protocolo = '$protocolo' ";
}

if (isset($_POST['numProcesso']) && $_POST['numProcesso'] != null) {
    $numProcesso = $_POST['num_processo'];
    $numProcesso = " AND fc.num_processo_pagto = '$numProcesso' ";
}

if (isset($_POST['status']) && $_POST['status'] != null) {
    $status = $_POST['status'];
    $status = " AND p.status_pedido_id = '$status' ";
}

$sql = "SELECT 
               p.id,
               p.numero_processo,
               fc.protocolo, 
               pf.nome,
               st.status
               FROM pedidos AS p 
               INNER JOIN formacao_contratacoes AS fc ON fc.id = p.origem_id
               INNER JOIN pessoa_fisicas AS pf ON fc.pessoa_fisica_id = pf.id
               INNER JOIN pedido_status AS st ON p.status_pedido_id = st.id
               WHERE p.origem_tipo_id = 2 AND p.publicado = 1 $status $numProcesso $protocolo";
$query = mysqli_query($con,$sql);
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Contabilidade</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Resultado da pesquisa</h3>
            </div>
            <div class="box-body">
                <table id="tblFormacao" class="table table-striped table-bordered">
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
                    while ($pedido = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td>
                                <form action="?perfil=contabilidade&p=formacao&sp=detalhes" role="form" method="POST">
                                    <input type="hidden" name="idPedido" id="idPedido" value="<?=$pedido['id']?>">
                                    <button type="submit" class="btn btn-primary"><?= $pedido['numero_processo'] ?></button>
                                </form>
                            </td>
                            <td><?= $pedido['protocolo'] ?></td>
                            <td><?= $pedido['nome'] ?></td>
                            <td><?= $pedido['status'] ?></td>
                        </tr>
                        <?php
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
                <a href="?perfil=contabilidade&p=formacao&sp=pesquisa">
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
        $('#tblFormacao').DataTable({
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