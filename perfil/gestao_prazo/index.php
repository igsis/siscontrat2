<?php
include "includes/menu.php";
$con = bancoMysqli();

$sql = "SELECT
               e.id AS 'id',
               e.protocolo AS 'protocolo', 
               e.nome_evento AS 'nome_evento',
               l.local AS 'local',
               e.suplente_id,
               e.fiscal_id
               FROM eventos AS e
               INNER JOIN pedidos AS p ON p.origem_id = e.id 
               INNER JOIN ocorrencias AS o ON o.origem_ocorrencia_id = e.id
               INNER JOIN locais AS l ON l.id = o.local_id
               WHERE evento_status_id = 2 AND e.publicado = 1 AND p.status_pedido_id = 1";
$query = mysqli_query($con,$sql);
$numEventos = mysqli_num_rows($query);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
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
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
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
