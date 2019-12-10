<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Buscar por Formacao</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3> Dados da Formação</h3>
                    </div>
                    <form action="?perfil=juridico&p=filtrar_formacao&sp=resultado_formacao" method="POST" ROLE="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="protocolo">Código do pedido</label>
                                    <input type="text" class="form-control" name="codigopedido" id="codigopedido">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="numprocesso">Número do Processo </label>
                                    <input type="text" class="form-control" name="numprocesso" id="numprocesso"
                                           data-mask="9999.9999/9999999-9" minlength="19">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="statuspedido">Status pedido</label>
                                    <select name="statuspedido" id="statuspedido" class="form-control">
                                        <option value="">Status pedido</option>
                                        <?php
                                        geraOpcao("pedido_status");
                                        ?>
                                    </select>
                                </div>
                            <div class="row">
                            </div>
                            <div class="box-footer">
                                <button type="submit" name="busca" id="busca" class="btn btn-primary pull-left">
                                    Pesquisar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </section>

</div>