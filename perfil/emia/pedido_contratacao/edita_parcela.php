<?php
$con = bancoMysqli();

$idPedido = $_GET['idPedido'];

$pedido = recuperaDados('pedidos', 'id', $idPedido);

$parcela = $con->query("SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND publicado = 1")->fetch_all(MYSQLI_ASSOC);
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Atualizar Parcelas do pedido</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Parcelas</h3>
                    </div>
                    <form method="POST" action="?perfil=emia&p=pedido_contratacao&sp=edita"
                          role="form">
                        <div class="box-body">
                            <?php
                            for ($i = 0; $i < $pedido['numero_parcelas']; $i++) {
                                ?>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="parcela[]">Parcela:</label>
                                        <input type="number" readonly class="form-control" value="<?= $i + 1 ?>"
                                               name="parcela[]" id="parcela[]" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="valor[]">Valor:</label>
                                        <input type="text" name="valor[<?= $i ?>]"
                                               maxlength="10" class="form-control valor"
                                               value="<?= dinheiroParaBr($parcela[$i]['valor'] ?? NULL) ?>">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="data_pagamento">Data pagamento: </label>
                                        <input type="date" name="data_pagamento[<?= $i ?>]" class="form-control"
                                               placeholder="DD/MM/AAAA" required
                                               value="<?= $parcela[$i]['data_pagamento'] ?? NULL?>">
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong> Valor Total: </strong> R$ <span
                                            id="valorTotal" data-id="<?=$pedido['valor_total']?>"> <?= dinheiroParaBr($pedido['valor_total']) ?></span>
                                </div>

                                <div class="col-md-4">
                                    <strong> Valor somado das parcelas: </strong> R$ <span
                                            id="totalSomado"> <?= dinheiroParaBr($pedido['valor_total']) ?></span>
                                </div>

                                <div class="col-md-2">
                                    <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                                    <input type="hidden" value="<?= $pedido['numero_parcelas'] ?>" name="numParcelas">
                                    <button type="submit" name="parcelaEditada" id="grava"
                                            class="btn btn-primary pull-right">
                                        Gravar
                                    </button>
                                </div>

                            </div>
                    </form>

                    <div style="text-align: center" id="msgValorOk">
                        <span class="text-success"
                              style="font-size: 20px"><strong>O valor das parcelas confere!</strong></span>
                    </div>

                    <div style="text-align: center" id="msgValorErrado">
                        <span class="text-danger"
                              style="font-size: 20px"><strong>O valor das parcelas difere</strong></span>
                    </div>

                    <div class="box-footer">
                        <form action="?perfil=emia&p=pedido_contratacao&sp=edita" method="POST" role="form">
                            <button type="submit" name="carregar"
                                    class="btn btn-default pull-left">
                                <input type="hidden" name="idEc" value="<?= $idPedido ?>">
                                Voltar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    let msgValorErrado = $('#msgValorErrado');
    msgValorErrado.hide();
    var valorSomado = $('#totalSomado');

    function valores() {
        var total = parseFloat($('#valorTotal').attr('data-id'));
        var sum = 0;
        let msgValorOk = $('#msgValorOk');
        var btnGravar = $('#grava');

        $(".valor").each(function () {
            let valor = $(this).val().replace('.', '').replace(',', '.');
            sum += +valor;
        });

        var diferenca = total - sum;

        valorSomado.text(sum.toFixed(2));

        if (diferenca != 0) {
            btnGravar.attr('disabled', true);
            msgValorOk.hide();
            msgValorErrado.show();
        } else {
            btnGravar.attr('disabled', false);
            msgValorOk.show();
            msgValorErrado.hide();
        }
    }

    $('.valor').keyup(valores);

    $(document).ready(function () {
        $('.valor').mask('00.000,00', {reverse: true});
    });
</script>



