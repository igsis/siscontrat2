<?php
include "includes/menu_principal.php";
unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START ACCORDION-->
        <h2 class="page-header">Seus últimos eventos enviados</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">

                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Evento ABC</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="box-body">
                                        <p><b>Enviado por:</b> Lorelei Lourenço (Secretaria Municipal de Cultura) <b>em:</b> 06/06/2017</p>
                                        <p><b>Data:</b> 10/06/2017</p>
                                        <p><b>Local:</b> Biblioteca Nuto Sant’anna (CSMB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
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
