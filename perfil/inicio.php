<?php
include "includes/menu.php";
$con = bancoMysqli();

if (isset($_SESSION['idEvento'])) {
    unset($_SESSION['idEvento']);
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START ACCORDION-->
        <h2 class="page-header"><?= saudacao() . ", " . $_SESSION['nome_s'] ?>!</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Mural de Atualizações</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            <?php
                            $sql_avisos = "SELECT * FROM avisos WHERE publicado = '1' ORDER BY data DESC";
                            $query_avisos = mysqli_query($con, $sql_avisos);
                            $i = 1;
                            $x = 1;
                            while ($avisos = mysqli_fetch_array($query_avisos)) {
                                $data = $avisos['data'];
                                $msg = $avisos['mensagem'];
                                $titulo = $avisos['titulo'];

                                if ($i == 1) {
                                    $cor = "box-warning";
                                    $aberto = "in";
                                } else {
                                    $cor = "box-info";
                                    $aberto = "";
                                }
                                ?>
                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                <div class="panel box <?= $cor ?>">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i?>">
                                                <?= exibirDataBr($data). "  " . $titulo ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?=$i?>" class="panel-collapse collapse<?=$aberto?>">
                                        <div class="box-body bg-warning">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4><?= $msg; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                            ?>
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
<!--
</section>-->


