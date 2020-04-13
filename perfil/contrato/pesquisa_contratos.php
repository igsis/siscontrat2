<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Busca</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Busca de contratos</h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=resultado_contratos"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="protocolo">Protocolo</label>
                                    <input type="text" name="protocolo" id="protocolo" class="form-control" data-mask="99999999.99999-S">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="num_processo">Número de processo</label>
                                    <input type="text" class="form-control" name="num_processo" id="num_processo" data-mask="9999.9999/9999999-9">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="evento">Nome do evento</label>
                                    <input type="text" class="form-control" name="evento" id="evento">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="projeto">Projeto especial</label>
                                    <select name="projeto" id="projeto" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('projeto_especiais');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="usuario">Fiscal, suplente ou usuário que cadastrou o evento</label>
                                    <select name="usuario" id="usuario" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios');
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="status">Pedido status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('pedido_status WHERE id != 3 ');
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
