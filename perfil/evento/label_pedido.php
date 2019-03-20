<?php
/**
 * Conteúdo da label "#pedido" do arquivo "finalizar.php"
 */

$pedido = $con->query("SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'")->fetch_assoc();
$verba = recuperaDados('verbas', 'id', $pedido['verba_id'])['verba'];

$parcelado = false;


?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do Pedido</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th width="40%">Verba:</th>
                            <td><?=$verba?></td>
                        </tr>
                        <tr>
                            <th width="40%">Valor Total:</th>
                            <td>R$ <?=dinheiroParaBr($pedido['valor_total'])?></td>
                        </tr>
                        <tr>
                            <th width="40%">Número de Parcelas:</th>
                            <td><?=$pedido['numero_parcelas']?></td>
                        </tr>
                        <tr>
                            <th width="40%">Data Kit Pagamento:</th>
                            <td><?=exibirDataBr($pedido['data_kit_pagamento'])?></td>
                        </tr>
                        <tr>
                            <th width="40%">Forma de Pagamento:</th>
                            <td><?=$pedido['forma_pagamento']?></td>
                        </tr>
                        <tr>
                            <th width="40%">Justificativa:</th>
                            <td><?=$pedido['justificativa']?></td>
                        </tr>
                        <tr>
                            <th width="40%">Observação:</th>
                            <td><?=$pedido['observacao']?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do Proponente</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th width="30%">Verba:</th>
                            <td><?=$verba?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>