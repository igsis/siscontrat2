<?php

$idPedido = $_SESSION['idPedido'];

$con = bancoMysqli();

include "includes/menu_interno.php";
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
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Parcelas - Outros</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=pedido_pagamento_outros" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text" id="valor_total" name="valor_total" class="form-control" value="<?= dinheiroParaBr($valor_total) ?>" onKeyPress="return(moeda(this,'.',',',event))">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                    <input type="date" id="data_kit_pagamento" name="data_kit_pagamento" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>