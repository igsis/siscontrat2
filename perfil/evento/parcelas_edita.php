<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];
$nParcelas = $_GET['nParcelas'];

$pedido = recuperaDados("pedidos", "id", $idPedido);

$queryParcelas = $con->query("SELECT * FROM parcelas WHERE pedido_id = '$idPedido'");

if ($queryParcelas->num_rows) {
    $parcelas = $queryParcelas->fetch_all(MYSQLI_ASSOC);
}

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
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <form action="?perfil=evento&p=pedido_parcelas" method="POST" role="form">
                    <input type="hidden" name="idPedido" value="<?=$idPedido?>">
                    <input type="hidden" name="nParcelas" value="<?=$nParcelas?>">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Parcelas</h3>
                        </div>

                        <div class="row" align="center">
                            <?= $mensagem ?? "" ?>
                        </div>

                        <div class="box-body">
                            <?php for ($i = 0; $i < $nParcelas; $i++):
                                if (isset($oficina)): ?>
                                    <div class='row'>
                                        <div class='form-group col-md-1'>
                                            <label for='parcela'>Parcela </label>
                                            <input type='number' value="" class='form-control' readonly>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='valor'>Valor </label>
                                            <input type='text' id='valor' name='valor[{{count}}]' value="{{valor}}"
                                                   placeholder="Valor em reais"
                                                   onkeypress="return(moeda(this, '.', ',', event))"
                                                   class='form-control'>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='data_inicial'>Data Inicial</label>
                                            <input type='date' id='data_inicial' value="{{inicial}}"
                                                   name='inicial[{{count}}]'
                                                   class='form-control'>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='data_final'>Data Final</label>
                                            <input type='date' id='data_final' value="{{final}}" name='final[{{count}}]'
                                                   class='form-control'>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='modal_data_kit_pagamento'>Data Kit Pagamento</label>
                                            <input type='date' id='modal_data_kit_pagamento' value="{{kit}}"
                                                   name='modal_data_kit_pagamento[{{count}}]' class='form-control'>
                                        </div>
                                        <div class='form-group col-md-2'>
                                            <label for='horas'>Horas</label>
                                            <input type='number' id='horas' value="{{horas}}" name='horas[{{count}}]'
                                                   class='form-control'>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class='row'>
                                        <div class='form-group col-md-2'>
                                            <label for='parcela'>Parcela </label>
                                            <input type='number' name="parcela[<?=$i?>]" value="<?=$i+1?>"
                                                   class='form-control' readonly>
                                        </div>
                                        <div class='form-group col-md-3'>
                                            <label for='valor'>Valor *</label>
                                            <input type='text' name='valor[<?=$i?>]' class='form-control valor'
                                                   value="<?=isset($parcelas[$i]['valor']) ? dinheiroParaBr($parcelas[$i]['valor']) : ''?>" required
                                                   placeholder="Valor em reais"
                                                   onkeypress="return(moeda(this, '.', ',', event));">
                                        </div>
                                        <div class='form-group col-md-4'>
                                            <label for='data_pagamento'>Data Kit Pagamento *</label>
                                            <input type='date' id='data_pagamento' required
                                                   value="<?=$parcelas[$i]['data_pagamento'] ?? ''?>"
                                                   name='data_pagamento[<?=$i?>]' class='form-control'>
                                        </div>
                                    </div>
                                <?php endif;
                            endfor; ?>

                            <div class="row">
                                <div class="col-md-offset-3 col-md-3">
                                    <div class="alert">
                                        <strong>Valor Total:</strong> R$ <span id="valorTotal"><?= $pedido['valor_total'] ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="alert">
                                        <strong>Valor Registrado:</strong> R$ <span id="totalRegistrado">0.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-offset-5 col-md-3 has-feedback">
                                    <strong class="text-success" id="alertValor">Valor OK</strong>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="?perfil=evento&p=pedido_parcelas" class="pull-left btn btn-default">Voltar</a>
                            <input type="submit" name="<?=$parcelas == null ? 'gravarParcelas' : 'editarParcelas'?>"
                                   class="pull-right btn btn-primary" value="Gravar" id="gravaParcelas">
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
    $(document).on("keyup", ".valor", function() {
        var btnGravar = $('#gravaParcelas');
        var total = parseFloat($('#valorTotal').text());
        var sum = 0;


        $(".valor").each(function(){
            let valor = $(this).val().replace('.', '').replace(',', '.');
            sum += +valor;
        });

        var diferenca = total - sum;
        $("#totalRegistrado").text(sum.toFixed(2));
        if (diferenca != 0) {
            btnGravar.attr('disabled', true);
        } else {
            btnGravar.attr('disabled', false);
        }
    });
</script>