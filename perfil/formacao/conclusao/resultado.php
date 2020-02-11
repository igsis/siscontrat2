<?php

$con = bancoMysqli();

$processo = $_POST['processo'] ?? NULL;
$protocolo = $_POST['protocolo'] ?? NULL;
$status = $_POST['status'] ?? NULL;

$sqlStatus = "";
$sqlProtocolo = "";
$sqlProcesso = "";

if($processo != NULL){
    $sqlProcesso = "AND p.numero_processo = '$processo'";
}

if($protocolo != NULL){
    $sqlProtocolo = "AND c.protocolo = '$protocolo'";
}

if($status != NULL){
    $sqlStatus = "AND p.status_pedido_id = '$status'";
}

$sql = "SELECT p.id, p.numero_processo, 
               pf.nome, s.status, c.protocolo
FROM pedidos AS p
INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
INNER JOIN pedido_status AS s ON p.status_pedido_id = s.id
INNER JOIN formacao_contratacoes AS c ON p.origem_id = c.id
WHERE p.publicado = 1 AND p.origem_tipo_id = 2
$sqlProcesso $sqlProtocolo $sqlStatus";

$query = mysqli_query($con, $sql);
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3 class="page-title">Formação - Conclusão</h3>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Resultado da busca</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped" id="tblResultado">
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
                                    while($pedido = mysqli_fetch_array($query)) { ?>
                                        <tr>
                                            <td>
                                                <form action="?perfil=formacao&p=conclusao&sp=concluir" method="POST">
                                                    <input type="hidden" name="idPedido" value="<?=$pedido['id']?>">
                                                    <button type="submit" class="btn btn-link"><?=$pedido['numero_processo']?></button>
                                                </form>
                                            </td>
                                            <td><?=$pedido['protocolo']?></td>
                                            <td><?=$pedido['nome']?></td>
                                            <td><?=$pedido['status']?></td>
                                        </tr>
                                    <?php } ?>
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
                </div>
                <div class="box-footer">
                    <a href="?perfil=formacao&p=conclusao&sp=pesquisa">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
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
