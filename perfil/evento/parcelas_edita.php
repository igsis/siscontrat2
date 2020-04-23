<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];

$pedido = recuperaDados("pedidos", "id", $idPedido);

$sqlOficina = "SELECT aa.acao_id FROM eventos e
                INNER JOIN atracoes a on e.id = a.evento_id
                INNER JOIN acao_atracao aa on a.id = aa.atracao_id
                WHERE e.id = '$idEvento' and a.publicado = 1";
$queryOficina = $con->query($sqlOficina);

while ($atracoes = $queryOficina->fetch_assoc()) {
    if ($atracoes['acao_id'] == 8) {
        $oficina = 1;
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contração</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <form action="?perfil=evento&p=pedido_parcelas" method="POST" role="form">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Parcelas</h3>
                        </div>

                        <div class="row" align="center">
                            <?= $mensagem ?? "" ?>
                        </div>

                        <div class="box-body">
                            <?php if (($pedido['numero_parcelas'] != null) || ($pedido['numero_parcelas'] != 0)):
                                for ($i = 0; $i < $pedido['numero_parcelas']; $i++):
                                    if ($oficina): ?>
                                        <div class='row'>
                                            <div class='form-group col-md-1'>
                                                <label for='parcela'>Parcela </label>
                                                <input type='number' value="{{count}}" class='form-control' disabled>
                                            </div>
                                            <div class='form-group col-md-2'>
                                                <label for='valor'>Valor </label>
                                                <input type='text' id='valor' name='valor[{{count}}]' value="{{valor}}" placeholder="Valor em reais"
                                                       onkeyup="somar()" onkeypress="return(moeda(this, '.', ',', event))" class='form-control'>
                                            </div>
                                            <div class='form-group col-md-2'>
                                                <label for='data_inicial'>Data Inicial</label>
                                                <input type='date' id='data_inicial' value="{{inicial}}" name='inicial[{{count}}]'
                                                       class='form-control'>
                                            </div>
                                            <div class='form-group col-md-2'>
                                                <label for='data_final'>Data Final</label>
                                                <input type='date' id='data_final' value="{{final}}" name='final[{{count}}]' class='form-control'>
                                            </div>
                                            <div class='form-group col-md-2'>
                                                <label for='modal_data_kit_pagamento'>Data Kit Pagamento</label>
                                                <input type='date' id='modal_data_kit_pagamento' value="{{kit}}"
                                                       name='modal_data_kit_pagamento[{{count}}]' class='form-control'>
                                            </div>
                                            <div class='form-group col-md-2'>
                                                <label for='horas'>Horas</label>
                                                <input type='number' id='horas' value="{{horas}}" name='horas[{{count}}]' class='form-control'>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class='row'>
                                            <div class='form-group col-md-2'>
                                                <label for='parcela'>Parcela </label>
                                                <input type='number' value="" class='form-control' readonly>
                                            </div>
                                            <div class='form-group col-md-3'>
                                                <label for='valor'>Valor *</label>
                                                <input type='text' id='valor' name='valor[]' value="" required
                                                       placeholder="Valor em reais"
                                                       onkeypress="return(moeda(this, '.', ',', event));" onkeyup="somar()" class='form-control'>
                                            </div>
                                            <div class='form-group col-md-4'>
                                                <label for='modal_data_kit_pagamento'>Data Kit Pagamento *</label>
                                                <input type='date' id='modal_data_kit_pagamento' value="{{kit}}" required
                                                       name='modal_data_kit_pagamento[{{count}}]'
                                                       class='form-control'>
                                            </div>
                                        </div>
                                    <?php endif;
                                endfor;
                            else: ?>
                                <div class="alert alert-info ">
                                    <h4><i class="icon fa fa-info"></i> Atenção!</h4>
                                    Este pedido não possui parcelas.
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-offset-3 col-md-3">
                                    <div class="alert">
                                        <strong>Valor Total:</strong> R$ <?=dinheiroParaBr($pedido['valor_total'])?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="alert">
                                        <strong>Valor Registrado:</strong> R$ 123
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="pull-right btn btn-primary next-step" id="next">Gravar</button>
                        </div>
                        <!-- /.box-footer-->
                    </div>
                </form>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<script>

</script>