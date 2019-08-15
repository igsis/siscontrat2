<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Cargo</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cargo</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=cargo&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="titulo">Cargo *</label>
                                    <input type="text" id="cargo" name="cargo" class="form-control" required>
                                </div>

                                <div class="form-group  col-md-8">
                                    <label for="msg">Justificativa *</label>
                                    <input type="text" id="justificativa" name="justificativa" class="form-control"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=cargo&spp=index">
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