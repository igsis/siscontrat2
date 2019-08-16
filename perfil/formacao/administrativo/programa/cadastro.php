<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Programa</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Programa</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=administrativo&sp=programa&spp=edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="programa">Programa *</label>
                                    <input type="text" id="programa" name="programa" required class="form-control">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="edital">Edital *</label>
                                    <input type="text" id="edital" name="edital" required class="form-control">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="verba">Verba *</label>
                                    <select name="verba" id="verba" required class="form-control">
                                        <?php
                                        geraOpcao('verbas');
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="descricao">Descrição *</label>
                                    <textarea name="descricao" id="descricao" rows="3" required class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=formacao&p=administrativo&sp=programa&spp=index">
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