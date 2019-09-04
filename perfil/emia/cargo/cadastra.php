<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Cadastro de Cargos</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cargo</h3>
            </div>
            <form method="post" action="?perfil=emia&p=cargo&sp=listagem" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <input class="form-control" type="text" required name="cargo" id="cargo">
                        </div>
                        <div class="col-md-8">
                            <label for="justificativa">Justificativa: *</label>
                            <input class="form-control" type="text" required name="justificativa" id="justificativa">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=cargo&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <button name="cadastra" id="cadastra" type="submit" class="btn btn-primary pull-right">Cadastrar </button>
            </form>
        </div>
    </section>
</div>

