<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Buscar</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> Busca por Formação</h3>
                    </div>
                    <form action="?perfil=juridico&p=filtrar_formacao&sp=resultado_formacao" method="POST" ROLE="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="protocolo">Protocolo</label>
                                    <input type="text" class="form-control" name="codigopedido" id="codigopedido">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="numprocesso">Número do Processo </label>
                                    <input type="text" class="form-control" name="numprocesso" id="numprocesso">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="statuspedido">Status do pedido</label>
                                    <select name="statuspedido" id="statuspedido" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                            <?php
                                                geraOpcaoStatus();
                                            ?>
                                    </select>
                                </div>
                            </div>
                                
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="busca" id="busca" class="btn btn-primary pull-right">
                                Pesquisar
                            </button>
                    </form>
                </div>
            </div>

        </div>

    </section>

</div>