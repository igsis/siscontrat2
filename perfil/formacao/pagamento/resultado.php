<?php
$con = bancoMysqli();

$protocolo = '';
$numProcesso = '';
$proponente = '';
$row = 0;
$where = '';

if (isset($_POST['protocolo']) && $_POST['protocolo'] != null) {
    $protocolo = $_POST['protocolo'];
    $protocolo = " AND fc.protocolo LIKE '$protocolo' ";
}

if (isset($_POST['numProcesso']) && $_POST['numProcesso'] != null) {
    $numProcesso = $_POST['numProcesso'];
    $numProcesso = " AND fc.num_processo_pagto LIKE '$numProcesso' ";
}

if (isset($_POST['proponente']) && $_POST['proponente'] != null) {
    $proponente = $_POST['proponente'];
    $proponente = " AND fc.pessoa_fisica_id LIKE '$proponente' ";
}

$sql = "SELECT fc.id, p.id as pedido_id, fc.protocolo, fc.pessoa_fisica_id, p.numero_processo FROM formacao_contratacoes fc INNER JOIN pedidos p on fc.id = p.origem_id WHERE p.origem_tipo_id = 2 AND fc.publicado = 1 $proponente $numProcesso $protocolo";
$query = mysqli_query($con, $sql);
$num_arrow = mysqli_num_rows($query);
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
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Processo</th>
                                <th>Protocolo</th>
                                <th>Proponente</th>
                                <th width="10%">Nota empenho</th>
                                <th width="5%">Pagamento</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if ($num_arrow == 0) {
                                ?>
                                <tr>
                                    <th colspan="5"><p align="center">Não foram encontrados registros</p></th>
                                </tr>
                                <?php
                            } else {
                                while ($formacao = mysqli_fetch_array($query)) {
                                    $proponente = recuperaDados('pessoa_fisicas', 'id', $formacao['pessoa_fisica_id'])['nome'];
                                    $pedido = recuperaDados('pedidos', 'origem_id', $formacao['pedido_id'] . "AND origem_tipo_id = 2");
                                    $idPedido = $formacao['pedido_id'];
                                    $sqlPagamento = "SELECT * FROM pagamentos WHERE pedido_id = '$idPedido'";
                                    $pagamento = mysqli_num_rows(mysqli_query($con, $sqlPagamento));
                                    if ($pagamento == 0)
                                        $action = "?perfil=formacao&p=pagamento&sp=empenho";
                                    else
                                        $action = "?perfil=formacao&p=pagamento&sp=empenho_edita";
                                    ?>
                                    <tr>
                                        <td><?= $formacao['numero_processo'] ?></td>
                                        <td><?= $formacao['protocolo'] ?></td>
                                        <td><?= $proponente ?></td>
                                        <td>
                                            <form action="<?= $action ?>"
                                                  method="POST">
                                                <input type="hidden" name="idPedido" id="idPedido"
                                                       value="<?= $idPedido ?>">
                                                <input type="hidden" name="idFC" id="idFC" value="<?=$formacao['id']?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block">
                                                    <b>N.E</b>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method='POST'
                                                  action="?perfil=formacao&p=pagamento&sp=pagamento">
                                                <input type="hidden" name='idFormacao' id="idFormacao"
                                                       value="<?= $formacao['id'] ?>">
                                                <button type="submit" name="carregar" id="carregar"
                                                        class="btn btn-primary btn-block">
                                                    <b>Pagto</b>
                                                </button>
                                            </form>
                                        </td>
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
                                <th width="10%">Nota empenho</th>
                                <th width="5%">Pagamento</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=formacao&p=pagamento&sp=index">
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