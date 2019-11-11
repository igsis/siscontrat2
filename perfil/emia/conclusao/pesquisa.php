<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Concluir processo</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Busca de pagamentos</h3>
                    </div>
                    <form method="POST" action="?perfil=emia&p=conclusao&sp=resultado"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="protocolo">Protocolo:</label>
                                    <input type="text" name="protocolo" id="protocolo" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="numProcesso">Número de processo:</label>
                                    <input type="text" id="numProcesso" name="numProcesso" data-mask="9999.9999/9999999-9" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="status">Status do Pedido:</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Selecione uma opção</option>
                                        <?php
                                            geraOpcao('pedido_status');
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="busca" id="busca" class="btn btn-primary pull-right">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


