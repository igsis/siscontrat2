<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Vigência</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vigência</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=vigencia&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="ano">Ano *</label>
                                    <input type="number" min="2018" id="ano" name="ano" required class="form-control">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="parcelas">Qtd. Parcelas *</label>
                                    <input type="number" min="1" id="num_parcela" name="num_parcela" required class="form-control">
                                </div>

                                <div class="form-group col-md-8">
                                    <label for="descricao">Descrição *</label>
                                    <input type="text" id="descricao" name="descricao" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=vigencia&spp=index">
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