<?php
$con = bancoMysqli();

$sql = "SELECT * FROM eventos WHERE evento_status_id = 2 AND publicado = 1";
$query = mysqli_query($con,$sql);
$numEventos = mysqli_num_rows($query);
?>

<div class="content-wrapper">
    <section class="content">
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-purple-active">
                <h3 class="widget-user-username">Módulo de Gestão de Prazos</h3>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 border-right">
                        <div class="description-block">
                            <span class="description-header">
                                Esse módulo tem a função de cancelar ou aprovar pedidos de contratação com prazos expirados.
                                <br/>
                                <br/>
                            </span></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            if($numEventos > 0){ ?>
                <div class="col-md-5 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-flag-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Eventos fora do prazo</span>
                            <span class="info-box-number"><?=$numEventos?></span>
                        </div>
                    </div>
                </div>
        <?php }?>
    </section>
</div>
