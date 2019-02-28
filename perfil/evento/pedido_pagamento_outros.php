<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];
$idPedido = $_SESSION['idPedido'];

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

if(isset($_POST['cadastra'])){
    $valor_total = $_POST['valor_total'];
    $data_kit_pagamento = $_POST['data_kit_pagamento'];
    $sql_cadastra = "UPDATE pedidos SET valor_total = '$valor_total', data_kit_pagamento = '$data_kit_pagamento' WHERE id = '$idPedido'";
    if(mysqli_query($con,$sql_cadastra)){
        header('Location: ?perfil=evento&p=pedido_cadastro');
    }
    else{
        $mensagem = mensagem("danger","Erro ao gravar: ". die(mysqli_error($con)));
    }
}

mysqli_close($con);
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