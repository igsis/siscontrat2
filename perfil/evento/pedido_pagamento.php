<?php

if(isset($_POST['pessoa_tipo_id'])){
    $pessoa_tipo_id = $_POST['pessoa_tipo_id'];
    $pessoa_id = $_POST['pessoa_id'];
}

if(isset($_POST['cadastra'])){
    $idEvento = $_SESSION['idEvento'];
    $pessoa_tipo_id = $_POST['pessoa_tipo_id'];
    $pessoa_id = $_POST['pessoa_id'];
    $numero_parcelas = $_POST['numero_parcelas'];
    if($pessoa_tipo_id == 1){
        $campo = "pessoa_fisica_id";
    }
    else{
        $campo = "pessoa_juridica_id";
    }

    $con = bancoMysqli();
    $sql = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, $campo, numero_parcelas, status_pedido_id) VALUES ('1', '$idEvento', '$pessoa_tipo_id', '$pessoa_id','$numero_parcelas', 1)";
    if(mysqli_query($con,$sql)){
        $idPedido = recuperaUltimo("pedidos");
        $_SESSION['idPedido'] = $idPedido;
        if($numero_parcelas == 0){
            header('Location: ?perfil=evento&p=pedido_pagamento_outros');
        }
        else{
            header('Location: ?perfil=evento&p=parcelas_cadastro');
        }
    }
    else{
        $mensagem = mensagem("danger","Erro ao gravar: ". die(mysqli_error($con)));
    }
    mysqli_close($con);
}

include "includes/menu_interno.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row" align="center">
            <?= $mensagem ?? NULL ?>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->

                <!-- pedido -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pagamento</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=pedido_pagamento" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="numero_parcelas">Número de Parcelas</label>
                                    <select class="form-control" id="numero_parcelas" name="numero_parcelas" required>
                                        <option value="">Selecione...</option>
                                        <option value="0">Outras</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                    </select>
                                </div>
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