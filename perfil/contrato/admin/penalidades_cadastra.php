<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastra Penalidade</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Penalidade</h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=admin&sp=penalidades_edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="modelo">Modelo: *</label>
                                    <input type="text" id="modelo" name="modelo" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-md-12">
                                    <label for="msg">Texto: *</label>
                                    <textarea id="texto" name="texto" class="form-control"
                                           required
                                           rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=contrato&p=admin&sp=penalidades_lista">
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