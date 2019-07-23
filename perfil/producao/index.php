<?php
include "../perfil/producao/includes/menu.php";
$con = bancoMysqli();

$sql = "SELECT * FROM eventos WHERE evento_status_id >= 3 AND publicado = 1 AND visualizado = 1";
$query = mysqli_query($con, $sql);
$numEventos = mysqli_num_rows($query);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-navy-active">
                <h3 class="widget-user-username">Módulo de Produção</h3>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 border-right">
                        <div class="description-block">
                            <?php //<span class="description-header">Esse módulo tem a função de cancelar ou aprovar pedidos de contratação com prazos expirados.<br/><br/></span> ?>
                            <span class="description-header">Esse módulo tem a função de listar os eventos da instituição e visualizar detalhes pertinentes à produção.<br/><br/></span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <div class="row">
            <!-- /.col -->
            <?php

            if ($numEventos > 0) {
                ?>
                <div class="col-md-5 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Eventos a serem visualizados</span>
                            <span class="info-box-number"><?= $numEventos ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
    </section>
</div>
