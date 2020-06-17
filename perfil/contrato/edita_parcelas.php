<?php
$con = bancoMysqli();

$idEvento = $_GET['id'];
$pedido = $con->query("SELECT id, numero_parcelas, valor_total FROM pedidos WHERE origem_id = $idEvento AND origem_tipo_id = 1 AND publicado = 1")->fetch_array();
$idPedido = $pedido['id'];

$consultaAcoes = $con->query("SELECT acao_id FROM atracoes AS a 
                                    INNER JOIN eventos AS e ON a.evento_id = e.id 
                                    INNER JOIN acao_atracao aa on a.id = aa.atracao_id 
                                    WHERE e.id = $idEvento");
while ($acoesArray = mysqli_fetch_array($consultaAcoes)) {
    if ($acoesArray['acao_id'] == 8) {
        $oficina = 1;
    } else {
        $oficina = 0;
    }
}


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
                    <form method="POST" action="?perfil=contrato&p=resumo"
                          role="form">
                        <div class="box-body">
                            <?php
                            for ($i = 1; $i < $pedido['numero_parcelas'] + 1; $i++) {

                                if ($oficina == 1) {
                                    ?>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="parcela[]">Parcela:</label>
                                            <input type="number" readonly class="form-control" value="<?= $i ?>"
                                                   name="parcela[]" id="parcela[]" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="valor[]">Valor:</label>
                                            <input type="text" id="valor[]" name="valor[]"
                                                   class="form-control" onKeyPress="return(moeda(this,'.',',',event))"
                                                   value="<?= dinheiroParaBr($parcela['valor']) ?>" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="data_pagamento">Data Inicial: </label>
                                            <input type="date" name="data_inicial[]" class="form-control"
                                                   id="datepicker12" placeholder="DD/MM/AAAA" required
                                                   value="<?= $parcela['data_inicial'] ?? NULL ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="data_pagamento">Data Final: </label>
                                            <input type="date" name="data_final[]" class="form-control"
                                                   id="datepicker12" placeholder="DD/MM/AAAA" required
                                                   value="<?= $parcela['data_final'] ?? NULL ?>">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="data_pagamento">Data Kit pagamento: </label>
                                            <input type="date" name="data_pagamento[]" class="form-control"
                                                   id="datepicker12" placeholder="DD/MM/AAAA" required
                                                   value="<?= $parcela['data_pagamento'] ?? NULL ?>">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="parcela[]">Carga Horária:</label>
                                            <input type="number" class="form-control" value="<?= $parcela['carga_horaria'] ?>"
                                                   name="parcela[]" id="parcela[]" required>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    $sql = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = '$i'";
                                    $parcela = mysqli_fetch_array(mysqli_query($con, $sql));
                                    ?>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="parcela[]">Parcela:</label>
                                            <input type="number" readonly class="form-control" value="<?= $i ?>"
                                                   name="parcela[<?=$i?>]" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="valor[<?=$i?>]">Valor:</label>
                                            <input type="text" name="valor[]"
                                                   class="form-control valor" onKeyPress="return(moeda(this,'.',',',event))"
                                                   value="<?= dinheiroParaBr($parcela['valor']) ?>" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="data_pagamento">Data pagamento: </label>
                                            <input type="date" name="data_pagamento[<?=$i?>]" class="form-control"
                                                   id="datepicker12" placeholder="DD/MM/AAAA" required
                                                   value="<?= $parcela['data_pagamento'] ?? NULL ?>">
                                        </div>
                                    </div>
                                <?php }
                            } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong> Valor Total: </strong> R$ <span id="valorTotal"> <?= dinheiroParaBr($pedido['valor_total'])?></span>
                                </div>

                                <div class="col-md-4">
                                    <strong> Valor somado das parcelas: </strong> R$ <span id="totalSomado"> <?= dinheiroParaBr($pedido['valor_total'])?></span>
                                </div>

                                <div class="col-md-2">
                                    <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                                    <button type="submit" name="parcelaEditada" id="grava"
                                            class="btn btn-primary pull-right">
                                        Gravar
                                    </button>
                                </div>

                            </div>
                    </form>

                    <div style="text-align: center" id="msgValorOk">
                        <span class="text-success" style="font-size: 20px"><strong>O valor das parcelas confere!</strong></span>
                    </div>

                    <div style="text-align: center" id="msgValorErrado">
                        <span class="text-danger" style="font-size: 20px"><strong>O valor das parcelas difere</strong></span>
                    </div>
                </div>
                <div class="box-footer">
                    <form action="?perfil=contrato&p=resumo" method="post">
                        <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                        <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                        <button type="submit" name="Voltar" class="btn btn-default pull-left">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
</div>
</section>
</div>

<script>
    let msgValorErrado = $('#msgValorErrado');
    msgValorErrado.hide();
    function valores() {
        var total = parseFloat($('#valorTotal').text());
        var sum = 0;
        let msgValorOk = $('#msgValorOk');
        var btnGravar = $('#grava');

        $(".valor").each(function(){
            let valor = $(this).val().replace('.', '').replace(',', '.');
            sum += +valor;
        });

        var diferenca = total - sum;

        $("#totalSomado").text(sum.toFixed(2).replace('.', ','));
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
</script>

