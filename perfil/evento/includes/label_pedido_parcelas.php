<?php
?>
form.
<h3 class="h2">1. Detalhes de parcelas</h3>
<form action="" class="formulario-ajax" name="form-valor-equipamento" role="form" data-etapa="Parcelas">
    <input type="hidden" name="_method" value="parcelas">
    <div class="row">
        <div class="form-group col-md-8">
            <label for="verba_id">Verba *</label>
            <select class="form-control" id="verba_id" name="verba_id" required>
                <option value="">Selecione...</option>
                <?php
                geraOpcao("verbas", $pedido['verba_id'])
                ?>
            </select>
        </div>

        <?php
        if ($pedido['origem_tipo_id'] != 2) {
            $readonly = 'readonly';
        } else {
            $readonly = '';
        }
        ?>
        <div class="form-group col-md-4">
            <label for="valor_total">Valor Total</label>
            <input type="text" onkeypress="return(moeda(this, '.', ',', event))"
                   id="valor_total" name="valor_total" class="form-control"
                   value="<?= dinheiroParaBr($pedido['valor_total']) ?>"
                   <?= $readonly ?>>
        </div>
    </div>
    <?php
    if (isset($oficina)) {
        ?>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="numero_parcelas">Número de Parcelas *</label>
                <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                        required>
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
            <button type="button" id="editarParcelas" class="btn btn-primary"
                    style="display: block; margin-top: 2.2%;">
                Editar Parcelas
            </button>
        </div>
        <?php

    } else {
        ?>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="numero_parcelas">Número de Parcelas *</label>
                <select class="form-control" id="numero_parcelas" name="numero_parcelas"
                        required>
                    <option value="">Selecione...</option>
                    <?php
                    geraOpcaoParcelas("parcela_opcoes", $pedido['numero_parcelas']);
                    ?>
                </select>
            </div>
            <!-- Button trigger modal -->
            <button type="button" id="editarParcelas" class="btn btn-primary"
                    style="display: block; margin-top: 2.2%;">
                Editar Parcelas
            </button>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="forma_pagamento">Forma de pagamento *</label><br/>
            <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                      rows="8"
                      <?= $pedido['numero_parcelas'] != 13 ? 'readonly' : '' ?>><?= $pedido['forma_pagamento'] ?></textarea>
        </div>
        <div class="form-group col-md-6">
            <label for="justificativa">Justificativa *</label><br/>
            <textarea id="justificativa" name="justificativa" class="form-control"
                      rows="8"><?= $pedido['justificativa'] ?></textarea>
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
    <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>">
    <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
    <ul class="list-inline pull-right">
        <li>
            <button type="submit" class="btn btn-primary next-step">Proxima etapa <span
                    aria-hidden="true">&rarr;</span></button>
        </li>
    </ul>
</form>
