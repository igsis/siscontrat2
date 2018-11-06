<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

$idPedido = $_SESSION['idEvento'];//provisório
//$idPedido = $_POST['idPedido'];
$pedido = recuperaDados("pedidos","id",$idPedido);
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
                        <h3 class="box-title">Parecer Artista Local</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
                        <div class="form-group">
                            <label for="topico1">Forma de pagamento</label><br/>
                            <textarea id="topico1" name="topico1" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="topico2">Forma de pagamento</label><br/>
                            <textarea id="topico2" name="topico2" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="topico3">Forma de pagamento</label><br/>
                            <textarea id="topico3" name="topico3" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="topico4">Forma de pagamento</label><br/>
                            <textarea id="topico4" name="topico4" class="form-control" rows="5"></textarea>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name = "edita" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>