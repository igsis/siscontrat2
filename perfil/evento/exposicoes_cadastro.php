<?php

$con = bancoMysqli();
include "includes/menu_interno.php";

if (isset($_POST['idAtracao'])){
    $idAtracao = $_POST['idAtracao'];
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Cadastro de Exposição</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Produtor do Evento</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="?perfil=evento&p=oficina_edita" method="post" role="form">
                        <div class="box-body">

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">Cancelar</button>
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
