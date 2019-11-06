<?php
$con = bancoMysqli();

$sql = "SELECT p.id,
		       ec.protocolo,
               p.numero_processo,
               pf.nome,
               l.local,
               ec.ano,
               v.verba,
               s.status
        FROM pedidos AS p
        INNER JOIN emia_contratacao AS ec ON ec.id = p.origem_id    
        INNER JOIN pessoa_fisicas AS pf ON pf.id = p.pessoa_fisica_id
        INNER JOIN locais AS l ON ec.local_id = l.id
        INNER JOIN verbas AS v ON p.verba_id = v.id
        INNER JOIN emia_status AS s on ec.emia_status_id = s.id
        WHERE p.publicado = 1  AND p.origem_tipo_id = 3";
$query = mysqli_query($con, $sql);
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">EMIA</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <h4 class="box-title">Listagem de pedidos de contratação</h4>
            </div>
            <div class="box-body">
                <table id="tblPedidos" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Processo</th>
                        <th>Proponente</th>
                        <th>Local</th>
                        <th>Ano</th>
                        <th>Verba</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <?php
                    while ($dados = mysqli_fetch_array($query)) {
                        echo "<tbody>";
                        echo "<td> 
                                <form method='POST' action='?perfil=emia&p=pedido_contratacao&sp=edita' role='form'>
                                    <input type='hidden' name='idEc' value='" . $dados['id'] . "'>
                                    <button type='submit' name='carregar' class='btn btn-block btn-primary'><span>" . $dados['protocolo'] . "</span></button>
                                </form> 
                              </td>";
                        echo "<td>" . $dados['numero_processo'] . "</td>";
                        echo "<td>" . $dados['nome'] . "</td>";
                        echo "<td>" . $dados['local'] . "</td>";
                        echo "<td>" . $dados['ano'] . "</td>";
                        echo "<td>" . $dados['verba'] . "</td>";
                        echo "<td>" . $dados['status'] . "</td>";
                        echo "</tbody>";
                    }
                    ?>
                    <tfoot>
                    <tr>
                        <th>Protocolo</th>
                        <th>Processo</th>
                        <th>Proponente</th>
                        <th>Local</th>
                        <th>Ano</th>
                        <th>Verba</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=emia">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblPedidos').DataTable({
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
