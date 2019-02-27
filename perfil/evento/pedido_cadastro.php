<?php
/*
 * TESTE
 */
$pessoa_tipo_id = 2;
$pessoa_id = 9;
/*
 *
 */

if(isset($_POST['pessoa_tipo_id'])){
    $pessoa_tipo_id = $_POST['pessoa_tipo_id'];
    $pessoa_id = $_POST['pessoa_id'];
}

$con = bancoMysqli();
$idEvento = $_SESSION['idEvento'];

$sql = "SELECT sum(valor_individual) AS valor_total FROM atracoes WHERE evento_id = '$idEvento'";
$query = mysqli_query($con,$sql);
$atracao = mysqli_fetch_array($query);
$valor_total = $atracao['valor_total'];
if($valor_total > 0.00){
    $valor_total = $atracao['valor_total'];
}
else{
    $valor_total = 0.00;
}

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

                <!-- pedido -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalhes</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="verba_id">Verba</label>
                                    <select class="form-control" id="verba_id" name="verba_id" required>
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("verbas","")
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text" id="valor_total" name="valor_total" class="form-control" value="<?= dinheiroParaBr($valor_total) ?>" onKeyPress="return(moeda(this,'.',',',event))">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="numero_parcelas">Número de Parcelas</label>
                                    <select class="form-control" id="numero_parcelas" name="numero_parcelas" required>
                                        <option value="">Selecione...</option>
                                        <option value="0">Outras</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                    <input type="date" id="data_kit_pagamento" name="data_kit_pagamento" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="forma_pagamento">Forma de pagamento</label><br/>
                                    <textarea id="forma_pagamento" name="forma_pagamento" class="form-control" rows="8" required></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="justificativa">Justificativa</label><br/>
                                    <textarea id="justificativa" name="justificativa" class="form-control" rows="8" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <input type="text" id="observacao" name="observacao" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="pessoa_tipo_id" value="<?= $pessoa_tipo_id ?>">
                            <input type="hidden" name="pessoa_id" value="<?= $pessoa_id ?>">
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
                <!-- /.pedido -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>

<script>
    $('#valor_total').mask('000.000.000.000.000,00', {reverse: true});
</script>