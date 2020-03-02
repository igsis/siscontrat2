<?php
$con = bancoMysqli();

$sqlLocais = "SELECT * FROM locais WHERE publicado = 2";
$queryLocais = mysqli_query($con, $sqlLocais);
$numeroLocais = mysqli_num_rows($queryLocais);

$sqlEspacos = "SELECT * FROM espacos WHERE publicado = 2";
$queryEspacos = mysqli_query($con, $sqlEspacos);
$numeroEspacos = mysqli_num_rows($queryEspacos);

@include "includes/menu.php";
?>

<div class="content-wrapper">
    <section class="content">
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">Módulo de Administrativo</h3>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 border-right">
                        <div class="description-block">
                            <span class="description-header">Esse módulo possibilita gerenciar espaços, usuários, projetos especiais e realizar manutenções no sistema.<br/><br/></span>
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
            if ($numeroLocais != 0) {
                ?>
                <div class="col-md-3 col-md-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Locais para aprovar</span>
                            <span class="info-box-number"><?= $numeroLocais ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }

            if ($numeroEspacos != 0) {
                ?>
                <div class="col-md-3 col-md-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Espaços para aprovar</span>
                            <span class="info-box-number"><?= $numeroEspacos ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <!-- /.col -->
        </div>

<!--
        <div class="box box-solid">
            <div class="box-body center-block" align="center">
                <img src="https://media.giphy.com/media/fdXR4TOByOVIwLNhlW/giphy.gif" alt="para bens"
                     class="img-responsive">
            </div>
        </div>
-->
    </section>
</div>