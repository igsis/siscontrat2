<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>EMIA - Parcelas</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cadastro de Parcela</h3>
            </div>
            <form method="post" action="?perfil=emia&p=vigencia&sp=listagem" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="numParcelas">Numero de Parcelas: *</label>
                            <input type="number" min="1" class="form-control" required name="numParcelas" id="numParcelas">
                        </div>

                        <div class="col-md-3">
                            <label for="valor">Valor: *</label>
                            <input type="number" class="form-control" required name="valor" id="valor">
                        </div>

                        <div class="col-md-3">
                            <label for="data_inicio">Data de Início: *</label>
                            <input type="date" class="form-control" required name="data_inicio" id="">
                        </div>

                        <div class="col-md-3">
                            <label for="data_fim">Data de Encerramento: *</label>
                            <input type="date" class="form-control" required name="data_fim" id="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="data_pgt">Data de Pagamento: *</label>
                            <input type="date" class="form-control" required name="data_pgt" id="data_pgt">
                        </div>

                        <div class="col-md-4">
                            <label for="mes_ref">Mês de Referência: *</label>
                            <input type="text" class="form-control" required name="mes_ref" id="mes_ref">
                        </div>

                        <div class="col-md-4">
                            <label for="carga_horaria">Carga Horaria: *</label>
                            <input type="number" class="form-control" required name="carga_horaria" id="carga_horaria">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="?perfil=emia&p=vigencia&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <button name="cadastra" id="cadastra" type="submit" class="btn btn-primary pull-right">Cadastrar</button>
            </form>
        </div>
    </section>
</div>

