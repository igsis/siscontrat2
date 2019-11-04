<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">EMIA - Cadastro de Vigência</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Vigência</h3>
            </div>

            <form method="post" action="?perfil=emia&p=administrativo&sp=vigencia&spp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2  form-group">
                            <label for="ano">Ano: *</label>
                            <input class="form-control" type="number" min="2018" required name="ano" id="ano">
                        </div>


                        <div class="col-md-2 form-group">
                            <label for="numParcelas">Numero de Parcelas: *</label>
                            <input type="number" min="1" class="form-control" required name="numParcelas"
                                   id="numParcelas">
                        </div>


                        <div class="col-md-8  form-group">
                            <label for="descricao">Descrição: *</label>
                            <input class="form-control" type="text" required name="desc" id="desc">
                        </div>

                    </div>
                </div>


                <div class="box-footer">
                    <a href="?perfil=emia&p=administrativo&sp=vigencia&spp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <button name="cadastra" id="cadastra" type="submit" class="btn btn-primary pull-right">Cadastrar
                    </button>
            </form>
        </div>
</div>
</section>
</div>
