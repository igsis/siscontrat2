<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados("eventos", "id", $idEvento);
$sql = "SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1'";
$query = $con->query($sql);
$pedido = $query->fetch_assoc();

$sqlOficina = "SELECT aa.acao_id FROM eventos e
                INNER JOIN atracoes a on e.id = a.evento_id
                INNER JOIN acao_atracao aa on a.id = aa.atracao_id
                WHERE e.id = '$idEvento' and a.publicado = 1";
$queryOficina = $con->query($sqlOficina);

while ($atracoes = $queryOficina->fetch_assoc()) {
    if ($atracoes['acao_id'] == 8) {
        $oficina = 1;
    }
}

$tipoPessoa = $pedido['pessoa_tipo_id'];
$tipoEvento = $evento['tipo_evento_id'];

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contração</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <form action="?perfil=evento&p=pedido_parcelas" method="POST" role="form">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Detalhes de parcelas</h3>
                        </div>

                        <div class="row" align="center">
                            <?= $mensagem ?? "" ?>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <?php if (isset($oficina)): ?>
                                    <div class="form-group col-md-6">
                                        <label for="numero_parcelas">Número de Parcelas *</label>
                                        <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                                                data-oficina="1" required onchange="formaPagamento()">
                                            <option value="">Selecione...</option>
                                            <?php
                                            if ($pedido['numero_parcelas'] == 3) {
                                                $option = 4;
                                            } elseif ($pedido['numero_parcelas'] == 4) {
                                                $option = 3;
                                            } else {
                                                $option = $pedido['numero_parcelas'];
                                            }
                                            geraOpcaoParcelas("oficina_opcoes", $option);
                                            ?>
                                        </select>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group col-md-6">
                                        <label for="numero_parcelas">Número de Parcelas *</label>
                                        <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                                                data-oficina="0" required onchange="formaPagamento()">
                                            <option value="">Selecione...</option>
                                            <?php geraOpcaoParcelas("parcela_opcoes", $pedido['numero_parcelas']); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
<!--                                <button type="button" id="editarParcelas" class="btn btn-primary" style="display: block; margin-top: 1.8%;">-->
<!--                                    Editar Parcelas-->
<!--                                </button>-->
                                <a href="?perfil=evento&p=parcelas_edita" class="btn btn-primary col-md-1" style="display: block; margin-top: 1.8%;">
                                    Editar Parcelas
                                </a>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="forma_pagamento">Forma de pagamento *</label><br/>
                                    <textarea id="forma_pagamento" name="forma_pagamento" class="form-control" required
                                              rows="8" <?= $pedido['numero_parcelas'] != 13 ? 'readonly' : '' ?>><?= $pedido['forma_pagamento'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="justificativa">Justificativa *</label><br/>
                                    <textarea id="justificativa" name="justificativa" class="form-control"
                                              required rows="8"><?= $pedido['justificativa'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação</label>
                                    <input type="text" id="observacao" name="observacao" class="form-control"
                                           maxlength="255" value="<?= $pedido['observacao'] ?>">
                                </div>
                            </div>
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="data_kit" id="dataKit">
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="pull-right btn btn-primary next-step" id="next">Gravar</button>
                        </div>
                        <!-- /.box-footer-->
                    </div>
                </form>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<script>
    function formaPagamento() {
        let selectParcela = $('#numero_parcelas');
        let oficina = selectParcela.data('oficina');
        let formaPagamento = $('#forma_pagamento');

        if (oficina) {
            if (selectParcela.val() == 6) {
                formaPagamento.attr('readonly', false);
            } else {
                formaPagamento.attr('readonly', true);
            }
            console.log("é oficina!");
        } else {
            if (selectParcela.val() == 13) {
                formaPagamento.attr('readonly', false);
            } else {
                formaPagamento.attr('readonly', true);
            }
        }
    }

    $(document).ready(formaPagamento());
</script>