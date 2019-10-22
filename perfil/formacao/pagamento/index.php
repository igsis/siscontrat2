<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Pagamentos</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Busca de pagamentos</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=pagamento&sp=resultado"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="protocolo">Protocolo</label>
                                    <input type="text" name="protocolo" id="protocolo" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="numProcesso">Número de processo</label>
                                    <input type="text" id="numProcesso" name="numProcesso" data-mask="9999.9999/9999999-9" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="proponente">Proponente</label>
                                    <select name="proponente" id="proponente" class="form-control">
                                        <option value="">Selecione uma opção</option>
                                        <?php
                                        geraOpcao('pessoa_fisicas');
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
