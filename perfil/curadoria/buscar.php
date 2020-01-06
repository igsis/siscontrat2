<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Busca</h2>
          <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar</h3>
                    </div>
                    <form method="POST" action="?perfil=curadoria&p=resultado" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="protocolo">Protocolo:</label>
                                    <input type="text" name="protocolo" id="protocolo" class="form-control">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="evento">Nome do Evento:</label>
                                    <input type="text" class="form-control" name="nomeEvento" id="evento">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="usuario">Fiscal, suplente ou usuário que cadastrou o evento</label>
                                    <select name="usuario" id="usuario" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="projeto">Projeto Especial</label>
                                    <select name="projeto" id="projeto" class="form-control">
                                        <option value="">Selecione um projeto...</option>
                                        <?php
                                        geraOpcao('projeto_especiais');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Selecione um status...</option>
                                        <?php
                                        geraOpcao('evento_status');
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="busca" class="btn btn-primary pull-right">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
