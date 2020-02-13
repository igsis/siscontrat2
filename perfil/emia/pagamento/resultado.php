<?php
$con = bancoMysqli();

$protocolo = '';
$numProcesso = '';
$proponente = '';
$where = '';

if (isset($_POST['protocolo']) && $_POST['protocolo'] != null) {
    $protocolo = $_POST['protocolo'];
    $protocolo = " AND ec.protocolo LIKE '$protocolo' ";
}

if (isset($_POST['numProcesso']) && $_POST['numProcesso'] != null) {
    $numProcesso = $_POST['numProcesso'];
    $numProcesso = " AND p.numero_processo LIKE '$numProcesso' ";
}

if (isset($_POST['proponente']) && $_POST['proponente'] != null) {
    $proponente = $_POST['proponente'];
    $proponente = " AND ec.pessoa_fisica_id LIKE '$proponente' ";
}

$sql = "SELECT p.id, 
               ec.protocolo,
               p.numero_processo,
               pf.nome, 
               s.status 
        FROM pedidos AS p 
        INNER JOIN emia_contratacao AS ec ON ec.id = p.origem_id
        INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
        INNER JOIN pedido_status AS s ON p.status_pedido_id = s.id
        WHERE p.origem_tipo_id = 3 AND ec.publicado = 1 AND p.publicado = 1 $proponente $numProcesso $protocolo";
?>

<div class="content-wrapper">
    <section class="content">
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
                                <th width="15%">Processo</th>
                                <th>Protocolo</th>
                                <th>Proponente</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($query = mysqli_query($con, $sql)) {
                                while ($ec = mysqli_fetch_array($query)) {?>
                                    <tr>
                                        <td>
                                            <form action="?perfil=emia&p=pagamento&sp=pagamento" method="post" role="form">
                                                <input type="hidden" name="idPedido" id="idPedido" value="<?= $ec['id'] ?>">
                                                <button type="submit" class="btn btn-primary center-block"><?= $ec['numero_processo'] ?></button>
                                            </form>
                                        </td>
                                        <td><?= $ec['protocolo'] ?></td>
                                        <td><?= $ec['nome'] ?></td>
                                        <td><?= $ec['status'] ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th width="15%">Processo</th>
                                <th>Protocolo</th>
                                <th>Proponente</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=emia&p=pagamento&sp=pesquisa">
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