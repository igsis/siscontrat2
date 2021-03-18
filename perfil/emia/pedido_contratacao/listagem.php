<?php
$con = bancoMysqli();

if(isset($_POST['deletar'])){
    $idPedido = $_POST['idPedido'];
    $apagaPedido = $con->query("UPDATE pedidos SET publicado = 0 WHERE id = $idPedido AND origem_tipo_id = 3");
    if($apagaPedido){
        $mensagem = mensagem("success", "Pedido apagado com sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro ao apagar pedido");
    }
}

$sql = "SELECT p.id,
		       ec.protocolo,
               p.numero_processo,
               p.origem_id,
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
        INNER JOIN pedido_status AS s ON p.status_pedido_id = s.id
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
                        <th>Apagar</th>
                    </tr>
                    </thead>
                    <?php
                    while ($dados = mysqli_fetch_array($query)) {
                        echo "<tbody>";
                        echo "<td> 
                                <form method='POST' action='?perfil=emia&p=pedido_contratacao&sp=edita' role='form'>
                                    <input type='hidden' name='idEc' value='" . $dados['id'] . "'>
                                    <button type='submit' name='carregar' class='btn btn-block btn-link'><span>" . $dados['protocolo'] . "</span></button>
                                </form> 
                              </td>";
                        echo "<td>" . $dados['numero_processo'] . "</td>";
                        echo "<td>" . $dados['nome'] . "</td>";
                        echo "<td>" . $dados['local'] . "</td>";
                        echo "<td>" . $dados['ano'] . "</td>";
                        echo "<td>" . $dados['verba'] . "</td>";
                        echo "<td>" . $dados['status'] . "</td>";
                        echo "<td width='7%'> 
                                    <button type='button' name='apagar' id='apaga' data-target='#modalExclusao' data-toggle='modal' data-id='" . $dados['id'] . "' class='btn btn-block btn-danger'><span class='glyphicon glyphicon-trash'></span></button> 
                              </td>";
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
                        <th>Apagar</th>
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

<div id="modalExclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o pedido?</p>
            </div>
            <div class="modal-footer">
                <form action="?perfil=emia&p=pedido_contratacao&sp=listagem" method="POST">
                    <input type="hidden" name="idPedido" id="idPedido" value="">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                    </button>
                    <input type="submit" class="btn btn-danger btn-outline" name="deletar" value="Excluir">
                </form>
            </div>
        </div>
    </div>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $('#modalExclusao').on('show.bs.modal', function (e) {
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('#idPedido').attr('value', `${id}`);
    })
</script>

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
