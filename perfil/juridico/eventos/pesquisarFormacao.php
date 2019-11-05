<?php
$con = bancoMysqli();
$codigopedido = "";
$numprocesso = "";
$statuspedido = "";

if (isset($_POST['codigopedido']) && $_POST['codigopedido'] != null) {
    $codigopedido = $_POST['codigopedido'];
    $codigopedido = " AND origem_id='$codigopedido'";
}
if (isset($_POST['numprocesso']) && $_POST['numprocesso'] != null) {
    $numprocesso = $_POST['numprocesso'];
    $numprocesso = "AND numero_processo='$numprocesso'";
}
if (isset($_POST['statuspedido']) && $_POST['statuspedido'] != null) {
    $statuspedido = $_POST['statuspedido'];
    $statuspedido = "AND status_pedido_id=$statuspedido'";
}
$sql = "SELECT numero_processo,
        status_pedido_id,
        origem_id,
        pessoa_fisica_id
        
        
        FROM pedidos WHERE publicado = 1 ";

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
                        <th>Codigo do pedido</th>
                        <th>Proponente</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>



                    </tbody>
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