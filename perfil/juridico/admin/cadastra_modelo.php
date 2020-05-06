<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastra Modelo</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Modelo</h3>
                    </div>
                    <form method="POST" action="?perfil=juridico&p=admin&sp=modelo_edita"
                          role="form">
                        <div class="box-body">
                        <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" id="nome" name="nome" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="amparo">Amparo: *</label>
                                    <input type="text" id="amparo" name="amparo" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-md-12">
                                    <label for="finalizacao">Finalização: *</label>
                                    <textarea id="finalizacao" name="finalizacao" class="form-control"
                                           required
                                           rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=juridico&p=admin&sp=lista_modelo">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>