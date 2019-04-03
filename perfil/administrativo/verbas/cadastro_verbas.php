<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Verba</h2>

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Verba</h3>
                    </div>

                    <form method="POST" action="?perfil=administrativo&p=verbas&sp=edita_verbas" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="titulo">Título *</label>
                                    <input align="center" type="text" id="titulo" name="titulo" class="form-control" required>
                                </div>
                            </div>

                            <div class="box-footer">
                                <a href="?perfil=administrativo&p=verbas&sp=verbas_lista">
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