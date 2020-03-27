<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Contabilidade - Busca</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Pesquisar eventos</h3>
            </div>
            <div class="box-body">
                <form action="?perfil=contabilidade&p=eventos&sp=resultado" role="form" method="POST">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="protocolo">Protocolo: </label>
                            <input class="form-control" type="text" name="protocolo" id="protocolo">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="num_processo">Numero do Processo: </label>
                            <input class="form-control" type="text" name="num_processo" id="num_processo" >
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
                                    geraOpcao('evento_status');
                                    ?>
                            </select>
                        </div>

                        
                    </div>

            </div>
            <div class="box-footer">
                <a href="?perfil=contabilidade">
                    <button type="button" class="btn btn-default pull-left">Voltar</button>
                </a>
                <button type="submit" class="btn btn-primary pull-right" name="buscar">Pesquisar</button>
                </form>
            </div>
        </div>
    </section>
</div>

