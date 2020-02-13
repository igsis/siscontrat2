<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header">Produção - Pesquisa</h3>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Exportar evento para Excel</h3>
            </div>
            <div class="box-body">
                <form role="form" action="?perfil=producao&p=eventos&sp=exporta" method="POST">
                    <div class="row">

                        <div class="col-md-12">
                            <label for="usuario">Inserido pelo usuario: </label>
                            <input type="text" class="form-control" name="usuario" id="usuario">
                        </div>


                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-6">
                            <label for="projeto">Projeto Especial: </label>
                            <select class="form-control" name="projeto" id="projeto">
                                <option value="">Selecione um projeto...</option>
                                <?php
                                geraOpcaoPublicado("projeto_especiais", "");
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="local">Local: </label>
                            <select class="form-control" name="local" id="local">
                                <option value="">Selecione um local...</option>
                                <?php
                                geraOpcao("locais", "");
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-6">
                            <label for="data_inicio">Data de Início: </label>
                            <input type="date" class="form-control" name="data_inicio" id="data_inicio">
                        </div>

                        <div class="col-md-6">
                            <label for="data_fim">Data de Encerramento: (apenas se for temporada)</label>
                            <input type="date" class="form-control" name="data_fim" id="data_fim">
                        </div>
                    </div>

            </div>
            <div class="box-footer">
                <button name="pesquisa" id="pesquisa" type="submit" class="btn btn-primary pull-right">Pesquisar</button>
                <a href="?perfil=producao">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
            </form>
        </div>
    </section>
</div>
