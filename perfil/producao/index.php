<?php
$con = bancoMysqli();

$sqlEventos = "SELECT e.id FROM eventos AS e
               INNER JOIN producao_eventos AS pe ON e.id = pe.evento_id WHERE visualizado = 0 AND e.publicado = 1 GROUP BY e.id";
$queryEventos = mysqli_query($con, $sqlEventos);
$numEventos = mysqli_num_rows($queryEventos);

$sqlAgendao = "SELECT a.id FROM agendoes AS a
INNER JOIN producao_agendoes AS pa ON a.id = pa.agendao_id WHERE pa.visualizado = 0 AND a.publicado = 1 GROUP BY a.id";
$queryAgendao = mysqli_query($con, $sqlAgendao);
$numAgendao= mysqli_num_rows($queryAgendao);

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

            <?php
            if ($numAgendao > 0) {
                ?>
                <div class="col-md-5 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Agendões a serem visualizados</span>
                            <span class="info-box-number"><?=$numAgendao?></span>
                        </div>
                    </div>
                </div>

                <?php
            }
            ?>
    </section>
</div>
