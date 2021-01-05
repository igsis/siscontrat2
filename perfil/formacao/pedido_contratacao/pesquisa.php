<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header">Formação - Pesquisa</h3>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Exportar pedido para Excel</h3>
            </div>
            <div class="box-body">
                <form role="form" action="?perfil=formacao&p=pedido_contratacao&sp=resultado_exporta" method="POST">
                    <div class="row">

                        <div class="col-md-6">
                            <label for="protocolo">Protocolo: </label>
                            <input type="text" class="form-control" name="protocolo">
                        </div>

                        <div class="col-md-6">
                            <label for="processo">Número de Processo: </label>
                            <input type="text" class="form-control" name="processo">
                        </div>

                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="proponente">Proponente: </label>
                            <select name="proponente" class="form-control">
                                <option value="">Selecione um proponente...</option>
                                <?php
                                geraOpcao('pessoa_fisicas', "");
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <button name="pesquisa" id="pesquisa" type="submit" class="btn btn-primary pull-right">Pesquisar
                </button>
                <a href="?perfil=formacao">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>
        </form>
    </section>
</div>


