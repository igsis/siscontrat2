<?php
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>Cadastro de Vigência</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
            <h3 class="box-title">Vigência</h3>
        </div>
            <form method="post" action="?perfil=emia&p=vigencia&sp=listagem" role="form">
                <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="ano">Ano: *</label>
                        <input class="form-control" type="number" min="2018" required name="ano" id="ano">
                    </div>
                    <div class="col-md-8">
                        <label for="descricao">Descrição: *</label>
                        <input class="form-control" type="text" required name="desc" id="desc">
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
