<?php
$con = bancoMysqli();
$idFormacao = $_POST['idFormacao'];

if (isset($_POST['salvar'])) {
    $num_processo_pagto = $_POST['num_processo_pagto'];
    $sql = "UPDATE formacao_contratacoes SET num_processo_pagto = '$num_processo_pagto' WHERE id = '$idFormacao'";

    if (mysqli_query($con, $sql)) {
        gravarLog($sql);

        $mensagem = mensagem("success", "Processo de pagamento salvo com sucesso.");
    } else {
        $mensagem = mensagem("danger", "Ocorreu um erro ao salvar o processo de pagamento. Tente novamente!");
    }
}

$formacao = recuperaDados('formacao_contratacoes', 'id', $idFormacao);
$pedido = recuperaDados('pedidos', 'origem_id', $idFormacao . ' AND origem_tipo_id = 2');
$pf = recuperaDados('pessoa_fisicas', 'id', $pedido['pessoa_fisica_id']);
$idPedido = $pedido['id'];
$sql = "SELECT * FROM parcelas where pedido_id = '$idPedido'";
$query = mysqli_query($con, $sql);
$num_arrow = mysqli_num_rows($query);

$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
$queryLocal = mysqli_query($con, $sqlLocal);

$local = "";
while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' / ';
}

$local = substr($local, 0, -3);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Pedido de pagamento da formação</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $pf['nome'] ?> (<?= $pf['cpf'] ?>)</h3>
                        <div class="row" align="center">
                            <?php if(isset($mensagem)){echo $mensagem;};?>
                        </div>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=pagamento&sp=pagamento"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="protocolo">Protocolo</label>
                                    <input type="text" class="form-control" value="<?= $formacao['protocolo'] ?>"
                                           disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Número do processo</label>
                                    <input type="text" class="form-control" value="<?= $pedido['numero_processo'] ?>"
                                           disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local</label>
                                    <input type="text" class="form-control" value="<?= $local ?>" disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-8 col-md-offset-2">
                                    <label for="num_processo_pagto">Número processo de pagamento</label>
                                    <input class="form-control" type="text" name="num_processo_pagto"
                                           id="num_processo_pagto" required value="<?= $formacao['num_processo_pagto'] ?>" data-mask="9999.9999/9999999-9">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="idFormacao" name="idFormacao" value="<?= $idFormacao ?>">
                            <button type="submit" name="salvar" id="salvar" class="btn btn-primary pull-right">
                                Salvar
                            </button>
                        </div>
                    </form>

                    <table id="tblParcela" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Parcela</th>
                            <th>Valor</th>
                            <th>Pagamento</th>
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
                            while ($parcela = mysqli_fetch_array($query)) {
                                ?>
                                <tr>
                                    <td><?= $parcela['numero_parcelas'] ?></td>
                                    <td><?= dinheiroParaBr($parcela['valor']) ?></td>
                                    <td><?= exibirDataBr($parcela['data_pagamento']) ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Parcela</th>
                            <th>Valor</th>
                            <th>Pagamento</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(function () {
        $('#tblParcela').DataTable({
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