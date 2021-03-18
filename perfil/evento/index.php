<?php
include "includes/menu_principal.php";

unset($_SESSION['idPedido']);

$con = bancoMysqli();

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$idUsuario = $_SESSION['usuario_id_s'];


$idUser = $_SESSION['usuario_id_s'];
$sql = "SELECT e.id, e.contratacao, e.nome_evento, u.nome_completo, ee.data_envio, e.usuario_id, e.suplente_id, e.fiscal_id FROM eventos AS e
        INNER JOIN evento_envios ee on e.id = ee.evento_id
        INNER JOIN usuarios u on e.usuario_id = u.id
        WHERE e.publicado = 1
            AND e.evento_status_id >= 3
            AND (e.suplente_id = '$idUsuario' OR e.fiscal_id = '$idUsuario' OR e.usuario_id = '$idUsuario') 
            AND ee.data_envio is not null
        GROUP BY e.id
        ORDER BY e.id DESC";

$query = $con->query($sql);
$linha = $query->num_rows;

?>

<div class="content-wrapper">
    <section class="content">
        <div class="row" align="center">
            <?php if (isset($mensagem2)) {
                echo $mensagem2;
            }; ?>
        </div>
        <h2 class="page-header">Seus últimos eventos enviados</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header">

                    </div>
                    <div class="box-body">
                        <div class="box-group" id="accordionEventos">
                            <?php
                            if (!$linha) {
                                $mensagem = mensagem("info", "Não existe eventos enviados!");
                            } else {
                                while ($evento = $query->fetch_assoc()) {

                                    if ($evento['contratacao']) {
                                        $sqlPedido = "SELECT p.id FROM pedidos AS p
                                                        WHERE p.origem_id = '{$evento['id']}'
                                                        AND p.publicado = 1
                                                        AND p.origem_tipo_id = 1
                                                        AND p.status_pedido_id = 2";
                                        if ($con->query($sqlPedido)->num_rows == 0) {
                                            continue;
                                        }
                                    }

                                    $locais = listaLocais($evento['id'], '1');

                                    if ($evento['fiscal_id'] == $idUser){
                                        $corRepresentativa = 'box-primary';
                                        $legenda = '<small class="label bg-primary">Fiscal</small>';
                                    }
                                    else if ($evento['suplente_id'] == $idUser){
                                        $corRepresentativa = 'box-warning';
                                        $legenda = '<small class="label bg-yellow-active">Suplente</small>';
                                    }
                                    else if ($evento['usuario_id'] == $idUser){
                                        $corRepresentativa = 'box-success';
                                        $legenda ='<small class="label bg-green-active">Usuário</small>';
                                    }

                                    ?>
                                    <div class="panel box <?= $corRepresentativa ?>">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordionEventos"
                                                   href="#collapse<?= $evento['id'] ?>">
                                                    <?= $evento['nome_evento'] ?>
                                                </a>
                                            </h4>
                                            <div class="box-tools pull-right" style="margin-top: 5px">
                                                <?= $legenda ?>
                                            </div>
                                        </div>
                                        <div id="collapse<?= $evento['id'] ?>" class="panel-collapse collapse">
                                            <div class="box-body">

                                                <p><b>Enviado por: </b><?= $evento['nome_completo'] ?>
                                                    <b>em:</b> <?= exibirDataHoraBr($evento['data_envio']) ?> </p>
                                                <p><b>Período:</b> <?= retornaPeriodoNovo($evento['id'], 'ocorrencias') ?> </p>
                                                <p><b>Local:</b> <?= $locais ?></p>
                                                <?php if (!$evento['contratacao']): ?>
                                                    <p><b>OBS:</b> Evento Sem Contratação</p>
                                                <?php endif; ?>
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
                </div>
            </div>
        </div>
    </section>
</div>
