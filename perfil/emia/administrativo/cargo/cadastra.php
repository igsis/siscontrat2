<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>EMIA - Cargos</h3>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cadastro de cargo</h3>
            </div>
            <form method="post" action="?perfil=emia&p=administrativo&sp=cargo&spp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <input class="form-control" type="text" required name="cargo" id="cargo">
                        </div>
                        <div class="col-md-8">
                            <label for="justificativa">Justificativa: *</label>
                            <textarea name="justificativa" id="justificativa" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=administrativo&sp=cargo&spp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <button name="cadastra" id="cadastra" type="submit" class="btn btn-primary pull-right">Cadastrar </button>
            </form>
        </div>
    </section>
</div>
