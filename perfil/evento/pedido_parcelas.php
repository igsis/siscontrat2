<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];

$pedido = recuperaDados("pedidos", "id", $idPedido);
$evento = recuperaDados('eventos', 'id', $idEvento);

$tipoEvento = $evento['tipo_evento_id'];

if ($pedido['origem_tipo_id'] != 2 && $tipoEvento != 2) {
    $readonly = 'readonly';
} else {
    $readonly = '';
}

$tipoPessoa = $pedido['pessoa_tipo_id'];
if ($tipoPessoa == 2) {
    $idProponente = $pedido['pessoa_juridica_id'];
} else {
    $idProponente = $pedido['pessoa_fisica_id'];
}


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

if ($pedido['numero_parcelas'] != null) {
    if (($oficina && $pedido['numero_parcelas'] != 6) || (!$oficina && $pedido['numero_parcelas'] != 13)) {
        $parcelas = $con->query("SELECT id FROM parcelas WHERE pedido_id = '$idPedido'")->num_rows;
    }
} else {
    $parcelas = 0;
}

if (isset($_POST['gravar'])) {
    $idVerba = $_POST["verba_id"];
    $valor_total = dinheiroDeBr($_POST["valor_total"]);
    $num_parcelas = $_POST["numero_parcelas"];
    $forma_pagamento = trim(addslashes($_POST["forma_pagamento"]));
    $justificativa = trim(addslashes($_POST["justificativa"]));
    $observacao = trim(addslashes($_POST["observacao"]));
    $idPedido = $_POST["idPedido"];
    $tipoPesso = $_POST["tipoPessoa"];
    $idProponent = $_POST["idProponente"];
    $data_kit_pagamento = $_POST["data_kit"];

    if ($num_parcelas == 1 || $num_parcelas == 13 || ($oficina && $num_parcelas == 6)) {
        $data_kit_pagamento = date('Y-m-d', strtotime("+1 days", strtotime($data_kit_pagamento)));
    } else {
        $queryParcela = "SELECT data_pagamento FROM parcelas WHERE pedido_id = '$idPedido' AND numero_parcelas = 1";
        $data_kit_pagamento = $con->query($queryParcela)->fetch_row()[0];
    }

    $query = "UPDATE pedidos SET 
                   verba_id = '$idVerba',
                   numero_parcelas = '$num_parcelas',
                   valor_total = '$valor_total',
                   forma_pagamento = '$forma_pagamento',
                   data_kit_pagamento = '$data_kit_pagamento',
                   justificativa = '$justificativa',
                   observacao = '$observacao'
                WHERE id = '$idPedido'";

    if ($con->query($query)) {
        $mensagem = $mensagem('success', 'Detalhes da parcela gravados no sistema');
    } else {
        $mensagem = $mensagem('danger', 'Erro ao gravar os dados. Tente novamente.');
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <form action="?perfil=evento&p=pedido_parcelas" id="form_parcelas" method="POST" role="form" data-parcelas="<?= $parcelas ?>">
                    <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                    <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>">
                    <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                    <input type="hidden" name="data_kit" id="dataKit">

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Detalhes de parcelas</h3>
                        </div>

                        <div class="row" align="center">
                            <?= $mensagem ?? "" ?>
                        </div>
                        <div id="mensagem-alerta">
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <label for="verba_id">Verba *</label>
                                    <select class="form-control" required id="verba_id" name="verba_id" >
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("verbas", $pedido['verba_id'])
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text" onkeypress="return(moeda(this, '.', ',', event))"
                                           id="valor_total" name="valor_total" class="form-control"
                                           value="<?= dinheiroParaBr($pedido['valor_total']) ?>"
                                        <?= $readonly ?>>
                                </div>
                            </div>

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
                                        <div class="has-error" id="msgParcelas">
                                            <span class="help-block text-danger"><strong>É necessário editar as parcelas</strong></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
<!--                                <button type="button" id="editarParcelas" class="btn btn-primary" style="display: block; margin-top: 1.8%;">-->
<!--                                    Editar Parcelas-->
<!--                                </button>-->
                                <a href="?perfil=evento&p=parcelas_edita" class="btn btn-primary col-md-1"
                                   style="margin-top: 1.8%;" id="btnParcelas">
                                    Editar Parcelas
                                </a>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="forma_pagamento">Forma de pagamento *</label><br/>
                                    <textarea id="forma_pagamento" name="forma_pagamento" class="form-control" required
                                              rows="8"><?= $pedido['forma_pagamento'] ?></textarea>
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
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input name="gravar" type="submit" class="pull-right btn btn-primary" id="btnGravar" value="Gravar"
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
        let btnParcelas = $('#btnParcelas');
        let msgParcelas = $('#msgParcelas');
        let btnGravar = $('#btnGravar');

        if (selectParcela.val() == 1) {
            $('#forma_pagamento').val('O pagamento se dará no 20º (vigésimo) dia após a data de entrega de toda documentação correta relativa ao pagamento.');
            formaPagamento.attr('readonly', true);
        }

        if (oficina) {
            if (selectParcela.val() == 6 || selectParcela.val() == 1) {
                if (selectParcela.val() != 1) {
                    formaPagamento.attr('readonly', false);
                }
                btnParcelas.hide();
                msgParcelas.hide();
                btnGravar.attr('disabled', false);

            } else {
                formaPagamento.attr('readonly', true);
                btnParcelas.show();
                msgParcelas.show();
                btnGravar.attr('disabled', true);
            }
        } else {
            if (selectParcela.val() == 13 || selectParcela.val() == 1) {
                if (selectParcela.val() != 1) {
                    formaPagamento.attr('readonly', false);
                }
                btnParcelas.hide();
                msgParcelas.hide();
                btnGravar.attr('disabled', false);
            } else {
                formaPagamento.attr('readonly', true);
                btnParcelas.show();
                msgParcelas.show();
                btnGravar.attr('disabled', true);
            }
        }
    }

    $(document).ready(formaPagamento());
    $(document).ready(function () {
        let parcelas = $('#form_parcelas').data('parcelas');
        let btnGravar = $('#btnGravar');
        let msgParcelas = $('#msgParcelas');

        if (parcelas) {
            btnGravar.attr('disabled', false);
            msgParcelas.hide();
        } else {
            btnGravar.attr('disabled', true);
            msgParcelas.show();
        }

        <?php if ($data_kit == null && $data_kit2 == 0): ?>
            $('.next-step').prop('disabled', true);
            $('#mensagem-alerta').append('<div class="alert alert-danger col-md-12" role="alert">Crie uma ocorrência antes de prosseguir com pedido.</div>');
        <?php else: ?>
            $('#dataKit').val("<?= $data_kit ?>");
        <?php endif; ?>
    });
</script>