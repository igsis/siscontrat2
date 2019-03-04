<!-- Modal -->
<div class="modal fade" id="modalParcelas" tabindex="-1" role="dialog" aria-labelledby="ModalLongTitle" aria-hidden="true">
    <form class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Editar Parcelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="?perfil=evento&p=modal_parcelas"></iframe>
                </div>
                <form method="POST" action="?perfil=evento&p=pedido_edita" name="form_modal" role="form">
                    <?php
                    $parcelas = "<span id='parcela' class='parcela'></span>";
                    echo $parcelas;
                    $i = 0;
                    while ($i <= $parcelas) {
                    ?>
                    <div class="form-group col-md-3">
                        <label for="valor">Valor </label>
                        <input type="number" id="valor" name="valor"
                               class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="modal_data_kit_pagamento">Data Kit
                            Pagamento</label>
                        <input type="date" id="modal_data_kit_pagamento"
                               name="modal_data_kit_pagamento"
                               class="form-control">
                    </div>
            </div>
            <?php
            $i++;
            echo $i;
            }
            ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
</div>
</div>