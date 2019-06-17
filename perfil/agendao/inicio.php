<?php
include "includes/menu_principal.php";

$con = bancoMysqli();

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$idUsuario = $_SESSION['idUser'];


$idUser = $_SESSION['idUser'];
$sql = "SELECT * FROM eventos WHERE publicado = 1 AND agendao = 1 AND evento_status_id >= 3 AND (suplente_id = '$idUsuario' OR fiscal_id = '$idUsuario' OR usuario_id = '$idUsuario') ORDER BY id DESC LIMIT 0,20";

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
        <h2 class="page-header">Seus últimos eventos internos enviados</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="box-group" id="accordionEventoInterno">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <?php
                            if ($tem == 0) {
                                $mensagem = mensagem("info", "Não existe eventos internos enviados!");
                            } else {
                                while ($evento = mysqli_fetch_array($query)) {
                                    $locais = listaLocais($evento['id'], '1');
                                    ?>
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordionEventoInterno"
                                                   href="#collapse<?=$evento['id']?>">
                                                    <?= $evento['nome_evento'] ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?=$evento['id']?>" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <?php
                                                $dataEnvio = recuperaDados('evento_envios', 'evento_id', $evento['id']);
                                                $usuario = recuperaDados('usuarios', 'id', $evento['usuario_id']);
                                                ?>
                                                <p><b>Enviado por: </b><?= $usuario['nome_completo'] ?>
                                                    <b>em:</b> <?= exibirDataBr($dataEnvio['data_envio']) ?> </p>
                                                <p><b>Período:</b> <?= retornaPeriodoNovo($evento['id']) ?> </p>
                                                <p><b>Local:</b> <?= $locais ?></p>
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
