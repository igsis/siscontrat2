<?php
include "includes/menu_principal.php";

$con = bancoMysqli();

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$idUsuario = $_SESSION['idUser'];


$sql = "SELECT eve.id, eve.nome_evento, usu.nome_completo, envi.data_envio, oco.data_inicio, loc.local 
                              FROM EVENTOS eve
                              INNER JOIN usuarios usu
                              ON eve.usuario_id = usu.id
                              INNER JOIN evento_envios envi 
                              ON eve.id = envi.evento_id
                              INNER JOIN atracoes atr
                              ON eve.id = atr.evento_id
                              INNER JOIN ocorrencias oco
                              ON atr.id = oco.origem_ocorrencia_id	
                              INNER JOIN locais loc
                              ON oco.local_id = loc.id
                              WHERE eve.publicado = 1
                              AND
                              ((eve.usuario_id = '$idUsuario') OR (eve.fiscal_id = '$idUsuario'))
                              AND eve.evento_status_id = 3
                              AND evento_interno = 0
                              ORDER BY eve.id DESC LIMIT 0,20";

$query = mysqli_query($con, $sql);
$linha = mysqli_num_rows($query);

if ($linha >= 1) {
    $tem = 1;
} else {
    $tem = 0;
}
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
                            <?php
                            if ($tem == 0) {
                                $mensagem = mensagem("info", "Não existe eventos enviados!");
                            } else {
                                while ($evento = mysqli_fetch_array($query)) {
                                    ?>
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                    <?= $evento['nome_evento'] ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <p><b>Enviado por: </b><?= $evento['nome_completo'] ?> <b>em:</b> <?= exibirDataBr($evento['data_envio']) ?> </p>
                                                <p><b>Data:</b> <?= exibirDataBr($evento['data_inicio']) ?> </p>
                                                <p><b>Local:</b> <?= $evento['local'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="row" align="center">
                                <?php if (isset($mensagem)) {
                                    echo $mensagem;
                                }; ?>
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
