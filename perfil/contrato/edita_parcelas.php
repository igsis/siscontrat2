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

$parcela = $con->query("SELECT p.id, p.valor, p.data_pagamento, pc.data_inicio, pc.data_fim, pc.carga_horaria 
                FROM parcelas AS p
                LEFT JOIN parcela_complementos pc on p.id = pc.parcela_id
                WHERE pedido_id = '$idPedido' AND p.publicado = 1")->fetch_all(MYSQLI_ASSOC);
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
                            for ($i = 0; $i < $pedido['numero_parcelas']; $i++) {
                                if ($oficina == 1) {
                                    ?>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="parcela[]">Parcela:</label>
                                            <input type="number" readonly class="form-control" value="<?= $i + 1?>"
                                                   name="parcela[]" required>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="valor[]">Valor:</label>
                                            <input type="text" name='valor[<?= $i ?>]'
                                                   class="form-control valor"
                                                   value="<?= dinheiroParaBr($parcela[$i]['valor']) ?>" maxlength="10"
                                                   required>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="data_inicio[]">Data Inicial: </label>
                                            <input type="date" name="data_inicio[<?= $i ?>]" class="form-control"
                                                   placeholder="DD/MM/AAAA"
                                                   value="<?= $parcela[$i]['data_inicio'] ?? NULL ?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="data_fim[]">Data Final: </label>
                                            <input type="date" name="data_fim[<?= $i ?>]" class="form-control"
                                                   placeholder="DD/MM/AAAA"
                                                   value="<?= $parcela[$i]['data_fim'] ?? NULL ?>">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="data_pagamento">Data Kit pagamento: </label>
                                            <input type="date" name="data_pagamento[<?= $i ?>]" class="form-control"
                                                   placeholder="DD/MM/AAAA" required
                                                   value="<?= $parcela[$i]['data_pagamento'] ?? NULL ?>">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="cargaHoraria[]">Carga Horária:</label>
                                            <input type="number" class="form-control"
                                                   value="<?= $parcela[$i]['carga_horaria'] ?>"
                                                   name="cargaHoraria[<?= $i ?>]">
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="parcela[]">Parcela:</label>
                                            <input type="number" readonly class="form-control" value="<?= $i + 1 ?>"
                                                   name="parcela[<?= $i ?>]" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="valor[]">Valor:</label>
                                            <input type="text" name="valor[<?= $i ?>]"
                                                   class="form-control valor" maxlength="10"
                                                   value="<?= dinheiroParaBr($parcela[$i]['valor']) ?? NULL ?>" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="data_pagamento">Data pagamento: </label>
                                            <input type="date" name="data_pagamento[<?= $i ?>]" class="form-control"
                                                   id="datepicker12" placeholder="DD/MM/AAAA" required
                                                   value="<?= $parcela[$i]['data_pagamento'] ?? NULL ?>">
                                        </div>
                                    </div>


                                    <?php
                                }
                            } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong> Valor Total: </strong> R$ <span
                                            id="valorTotal"> <?= dinheiroParaBr($pedido['valor_total']) ?></span>
                                </div>

                                <div class="col-md-4">
                                    <strong> Valor somado das parcelas: </strong> R$ <span
                                            id="totalSomado"> <?= dinheiroParaBr($pedido['valor_total']) ?></span>
                                </div>

                                <div class="col-md-2">
                                    <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                                    <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                                    <input type="hidden" value="<?= $oficina ?>" name="checaOficina">
                                    <input type="hidden" value="<?= $i ?>" name="numParcelas">
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
    var valorSomado = $('#totalSomado');

    function valores() {
        var total = parseFloat($('#valorTotal').text());
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

