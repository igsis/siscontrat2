<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3 class="page-title">Formação - Conclusão</h3>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pesquisar</h3>
            </div>

            <div class="box-body">
                <form action="?perfil=formacao&p=conclusao&sp=resultado" role="form" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="protocolo">Protocolo: </label>
                            <input type="text" name="protocolo" id="protocolo" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="processo">Processo: </label>
                            <input type="text" name="processo" id="processo" class="form-control" minlength="19" data-mask="9999.9999/9999999-9">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="status">Status do Pedido: </label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Selecione um status...</option>
                                <?php
                                    geraOpcao("pedido_status");
                                ?>
                            </select>
                        </div>
                    </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Pesquisar</button>
            </form>
            
                <a href="?perfil=formacao">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>
    </section>
</div>