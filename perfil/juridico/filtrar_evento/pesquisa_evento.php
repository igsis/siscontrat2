<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Buscar por evento</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3> Dados Eventos</h3>
                    </div>
                    <form action="?perfil=juridico&p=eventos&sp=pesquisar" method="POST" ROLE="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="protocolo">Protocolo</label>
                                    <input type="text" class="form-control" name="protocolo" id="protocolo">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="numprocesso">Número do Processo </label>
                                    <input type="text" class="form-control" name="numprocesso" id="numprocesso"
                                           data-mask="9999.9999/9999999-9" minlength="19">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="objetoevento">Objeto/Evento </label>
                                    <input type="text" class="form-control" name="objetoevento" id="objetoevento">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="usuariocadastro">Fical,suplente ou usuário que cadastrou o evento</label>
                                    <select name="usuariocadastro" id="usuariocadastro" class="form-control">

                                        <option value="0">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios');
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="tipoevento">Tipo de
                                        Evento </label>
                                    <select name="tipoevento" id="tipoevento" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('tipo_eventos');
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="instituicao">Instituição</label>
                                    <select name="instituicao" id="instituicao" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('instituicoes');
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="statuspedido">Status pedido</label>
                                    <select name="statuspedido" id="statuspedido" class="form-control">
                                        <option value="">Status pedido</option>
                                        <?php
                                        geraOpcao("pedido_status");
                                        ?>
                                    </select>
                                </div>
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